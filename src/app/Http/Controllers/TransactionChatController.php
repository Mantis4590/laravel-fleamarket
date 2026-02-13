<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\TransactionMessage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionMessageRequest;
use App\Http\Requests\UpdateTransactionMessageRequest;
use App\Models\TransactionRating;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompletedMail;

class TransactionChatController extends Controller
{
    public function show(Item $item)
    {
        $currentUserId = Auth::id();

        // 取引関係者チェック（出品者 or 購入者だけ見れる）
        if ($item->user_id !== $currentUserId && $item->buyer_id !== $currentUserId) {
            abort(403);
        }

        // 既読更新
        if ($item->buyer_id === $currentUserId) {
            $item->update(['buyer_last_read_at' => now()]);
        } elseif ($item->user_id === $currentUserId) {
            $item->update(['seller_last_read_at' => now()]);
        }

        // 左サイド：自分の取引中一覧
        $transactions = Item::query()
            ->whereNotNull('buyer_id')
            ->where(function ($itemQuery) use ($currentUserId) {
                $itemQuery->where('user_id', $currentUserId)
                    ->orWhere('buyer_id', $currentUserId);
            })
            ->orderByDesc('last_message_at')
            ->get();

        // メイン：メッセージ一覧
        $messages = $item->transactionMessages()
            ->with('sender')
            ->oldest()
            ->get();

        // 更新後の値を反映
        $item->refresh();

        // チャット相手（表示用）
        $partner = ($item->buyer_id === $currentUserId) ? $item->seller : $item->buyer;

        // 自分が買い手/売り手か
        $isBuyer = ($currentUserId === $item->buyer_id);
        $isSeller = ($currentUserId === $item->user_id);

        // 購入者/出品者が評価済みか
        $buyerHasRated = TransactionRating::where('item_id', $item->id)
            ->where('rater_id', $item->buyer_id)
            ->exists();

        $sellerHasRated = TransactionRating::where('item_id', $item->id)
            ->where('rater_id', $item->user_id)
            ->exists();

        // モーダル開閉条件
        $isRatingModalOpen =
            ($isBuyer && request()->boolean('showRating') && !$buyerHasRated)
            || ($isSeller && $buyerHasRated && !$sellerHasRated);

        // 評価対象（相手）
        $rateeUser = $isBuyer ? $item->seller : $item->buyer;

        // 自分の評価（星の初期値）
        $myRating = TransactionRating::where('item_id', $item->id)
            ->where('rater_id', $currentUserId)
            ->value('rating');

        return view('transactions.chat', compact(
            'item',
            'transactions',
            'messages',
            'partner',
            'isRatingModalOpen',
            'rateeUser',
            'myRating'
        ));

    }

    public function store(StoreTransactionMessageRequest $request, Item $item)
    {
        $userId = Auth::id();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('transaction_messages', 'public');
        }

        TransactionMessage::create([
            'item_id' => $item->id,
            'sender_id' => $userId,
            'body' => $request->input('body'),
            'image_path' => $imagePath,
        ]);

        $item->update(['last_message_at' => now()]);

        return redirect()->route('transactions.chat', $item);
    }

    public function update(UpdateTransactionMessageRequest $request, Item $item, TransactionMessage $message)
    {
        $userId = Auth::id();

        // ✅ 取引関係者チェック（出品者 or 購入者）
        if ($item->user_id !== $userId && $item->buyer_id !== $userId) {
        abort(403);
        }

        // ✅ URL改ざん対策：このメッセージがこのitemの物か
        if ($message->item_id !== $item->id) {
            abort(404);
        }

        // ✅ 本人だけ編集可
        if ($message->sender_id !== $userId) {
            abort(403);
        }

        $message->update([
            'body' => $request->input('body'),
        ]);

        return redirect()->route('transactions.chat', $item);
    }

    public function destroy(Item $item, TransactionMessage $message)
    {
        $userId = Auth::id();

        // ✅ 取引関係者チェック（出品者 or 購入者）
        if ($item->user_id !== $userId && $item->buyer_id !== $userId) {
            abort(403);
        }

        // ✅ URL改ざん対策
        if ($message->item_id !== $item->id) {
            abort(404);
        }

        // ✅ 本人だけ削除可
        if ($message->sender_id !== $userId) {
            abort(403);
        }

        $message->delete();

        // ✅ last_message_at を最新に更新（最後のメッセージ消したとき用）
        $latest = $item->transactionMessages()->latest('created_at')->first();
        $item->update(['last_message_at' => optional($latest)->created_at]);

        return redirect()->route('transactions.chat', $item);
    }

    public function storeRating(Request $request, Item $item)
    {
        $currentUserId = Auth::id();

        // 取引関係者チェック
        if ($item->user_id !== $currentUserId && $item->buyer_id !== $currentUserId) {
            abort(403);
        }

        // ✅ 評価は1回だけ
        $alreadyRated = TransactionRating::where('item_id', $item->id)
            ->where('rater_id', $currentUserId)
            ->exists();

        if ($alreadyRated) {
            return redirect()->route('transactions.chat', $item);
        }

        // ✅ 出品者は「購入者が評価済み」のときだけ評価できる
        $isSeller = ($item->user_id === $currentUserId);
        if ($isSeller) {
            $buyerHasRated = TransactionRating::where('item_id', $item->id)
                ->where('rater_id', $item->buyer_id)
                ->exists();

            if (!$buyerHasRated) {
                abort(403);
            }
        }

        $validator = Validator::make(
            $request->all(),
            ['rating' => ['required', 'integer', 'min:1', 'max:5']],
            [
                'rating.required' => '評価を選択してください',
                'rating.min' => '評価は1〜5で選択してください',
                'rating.max' => '評価は1〜5で選択してください',
            ]
        );

        // ✅ 失敗したら「チャット画面に戻ってモーダルを開く」
        if ($validator->fails()) {
            return redirect()
                ->route('transactions.chat', ['item' => $item, 'showRating' => 1])
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // 評価される側（相手）
        $transactionPartner = ($item->buyer_id === $currentUserId) ? $item->seller : $item->buyer;
        if (!$transactionPartner) {
            abort(404);
        }

        TransactionRating::create([
            'item_id' => $item->id,
            'rater_id' => $currentUserId,
            'ratee_id' => $transactionPartner->id,
            'rating' => $validated['rating'],
        ]);

        // ✅ 購入者が評価した = 取引完了 → 出品者へメール
        $isBuyer = ($item->buyer_id === $currentUserId);
        if ($isBuyer) {
            $seller = $item->seller; // 出品者
            $buyer  = $item->buyer;  // 購入者

            if ($seller && $seller->email) {
                Mail::to($seller->email)->send(
                    new TransactionCompletedMail(item: $item, buyer: $buyer, seller: $seller)
                );
            }
        }

        return redirect()->route('home');
    }

}

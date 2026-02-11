<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\TransactionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionMessageRequest;

class TransactionChatController extends Controller
{
    public function show(Item $item)
    {
        $userId = Auth::id();

        // 取引関係者チェック（出品者 or 購入者だけ見れる）
        if ($item->user_id !== $userId && $item->buyer_id !== $userId) {
            abort(403);
        }

        // 左サイド：自分の取引中一覧
        $transactions = Item::query()
            ->whereNotNull('buyer_id')
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhere('buyer_id', $userId);
            })
            ->orderByDesc('last_message_at')
            ->get();

        // メイン：メッセージ一覧
        $messages = $item->transactionMessages()
            ->with('sender')
            ->oldest()
            ->get();

        // 既読更新（A案）
        if ($item->buyer_id === $userId) {
            $item->update(['buyer_last_read_at' => now()]);
        } elseif ($item->user_id === $userId) {
            $item->update(['seller_last_read_at' => now()]);
        }

        $partner = ($item->buyer_id === $userId)
            ? $item->seller
            : $item->buyer;

        return view('transactions.chat', compact(
            'item',
            'transactions',
            'messages',
            'partner'
        ));

    }

    public function store(StoreTransactionMessageRequest $request, Item $item)
    {
        $userId = Auth::id();

        TransactionMessage::create([
            'item_id' => $item->id,
            'sender_id' => $userId,
            'body' => $request->input('body'),
            'image_path' => null, // 画像保存やるならここ更新
        ]);

        $item->update(['last_message_at' => now()]);

        return redirect()->route('transactions.chat', $item);
    }
}

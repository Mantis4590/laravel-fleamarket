<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\TransactionRating;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        $transactionItems = Item::query()
            ->whereNotNull('buyer_id')
            ->where(function ($transactionItemQuery) use ($user) {
                $transactionItemQuery->where('user_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
            })
            ->orderByDesc('last_message_at')
            ->get();

        // 各取引商品に「未読件数」を付与（相手から来た分だけカウント）
        foreach ($transactionItems as $transactionItem) {

            $isBuyer = ($transactionItem->buyer_id === $user->id);

            $currentUserLastReadAt = $isBuyer
                ? $transactionItem->buyer_last_read_at
                : $transactionItem->seller_last_read_at;

            $unreadMessageCount = $transactionItem->transactionMessages()
                ->when(
                    $currentUserLastReadAt,
                    function ($messageQuery) use ($currentUserLastReadAt) {
                        $messageQuery->where('created_at', '>', $currentUserLastReadAt);
                    }
                )
                ->where('sender_id', '!=', $user->id)
                ->count();

            // Bladeで使うのでプロパティとして持たせる
            $transactionItem->unread_count = $unreadMessageCount;
        }

        // タブ横の数字（未読がある取引の「商品数」）
        $unreadTransactionCount = $transactionItems
            ->sum('unread_count');

        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
            $items = Item::where('buyer_id', $user->id)->latest()->get();
        } elseif ($page === 'transaction') {
            $items = $transactionItems;
        } else {
            $items = collect();
        }

        // 受けた評価の平均（ratee_id = 自分）
        // 評価0件なら avg は null になる
        $avgRating = TransactionRating::where('ratee_id', $user->id)->avg('rating');
        $ratingCount = TransactionRating::where('ratee_id', $user->id)->count();

        // 小数がある場合は四捨五入して整数に
        $avgRatingRounded = is_null($avgRating) ? null : (int) round($avgRating);

        return view('mypage.mypage', compact('user', 'items', 'page', 'avgRatingRounded', 'ratingCount', 'unreadTransactionCount'));
    }
}

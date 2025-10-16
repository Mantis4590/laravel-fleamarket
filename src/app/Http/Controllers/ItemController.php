<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request) {
        // クエリパラメータでタブ状態を判定
        $tab = $request->query('tab', 'recommend'); // デフォルトはおすすめ

        if ($tab === 'mylist' && auth()->check()) {
            // ログイン中の「いいね」した商品だけ取得
            $items = auth()->user()->likedItems()->get();
        } else {
            // おすすめタグ(または未ログイン)
            $items = Item::all();
        }

        return view('items.index', compact('tab', 'items'));
    }

    public function guestIndex() {
        $items = Item::all();

        return view('items.index_guest', compact('items'));
    }

    public function show($item_id) {
        $item = Item::with(['category', 'comments.user', 'likes'])->findOrFail($item_id);

        if (auth()->check()) {
            return view('items.show', compact('item'));
        } else {
            return view('items.show_guest', compact('item'));
        }
    }
}
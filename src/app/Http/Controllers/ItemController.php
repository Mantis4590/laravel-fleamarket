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

        return view('index', compact('tab', 'items'));
    }

    public function guestIndex() {
        $items = Item::all();

        return view('index_guest', compact('items'));
    }
}
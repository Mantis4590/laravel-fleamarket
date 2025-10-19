<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
{
    $tab = $request->query('tab', 'recommend');
    $keyword = $request->input('keyword'); // 検索ワードを取得

    if ($tab === 'mylist') {
        if (Auth::check()) {
            $query = Item::whereHas('likes', function ($likeQuery) {
                $likeQuery->where('user_id', Auth::id());
            });
        } else {
            $items = collect();
            return view('items.index', compact('tab', 'items'));
        }
    } else {
        $query = Item::query();
    }

    // 🔍 部分一致検索
    if ($keyword) {
        $query->where('name', 'like', "%{$keyword}%");
    }

    $items = $query->get();

    return view('items.index', compact('tab', 'items'));
}


    public function guestIndex() {
        $items = Item::all();

        return view('items.index_guest', compact('items'));
    }

    public function show($item_id) {
        $item = Item::with(['categories', 'comments.user', 'likes'])->findOrFail($item_id);

        if (auth()->check()) {
            return view('items.show', compact('item'));
        } else {
            return view('items.show_guest', compact('item'));
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;

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

        // 自分が出品した商品を除外
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
    }

    // 🔍 部分一致検索
    if ($keyword) {
        $query->where('name', 'like', "%{$keyword}%");
    }

    $items = $query->get();

    return view('items.index', compact('tab', 'items', 'keyword'));
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

    public function store(ExhibitionRequest $request) {
        // validated() で安全なデータのみ取得
        $validated = $request->validated();

        $path = $request->file('img_url')->store('images', 'public');

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'brand' => $request->brand,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'img_url' => $path,
        ]);

        $item->categories()->attach($validated['category_ids']);

        return redirect()->route('home')->with('success', '商品を出品しました！');
    }

    public function create() {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }
}
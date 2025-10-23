<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function show($item_id) {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        return view('purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, $item_id) {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 住所が未登録ならエラー返す
        if (empty($user->address) || empty($user->postcode)) {
            return back()->with('error', 'プロフィールに配送先住所を登録してください');
        }

        // すでに購入済みなら何もしない
        if ($item->buyer_id) {
            return redirect()->route('home')->with('error', 'この商品はすでに購入されています');
        }

        // ログイン中のユーザーを購入者として登録
        $item->buyer_id = auth()->id();
        $item->save();

        return redirect()->route('home')->with('success', '購入が完了しました！');
    }
}

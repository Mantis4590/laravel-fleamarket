<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request) {
        // クエリパラメータでタブ状態を判定
        $tab = $request->query('tab', 'recommend'); // デフォルトはおすすめ

        // ログイン状態で分岐
        if (Auth::check()) {
            // 認証済みならログイン後のトップ画面を表示
            return view('index', compact('tab'));
        } else {
            // 未ログインならゲスト用トップ画面を表示
            return view('index_guest');
        }
    }
}

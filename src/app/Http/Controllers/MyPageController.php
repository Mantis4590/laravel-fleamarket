<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MyPageController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        }
        elseif ($page === 'buy') {
            $items = collect();
        }

        return view('mypage.mypage', compact('user', 'items', 'page'));
    }
}

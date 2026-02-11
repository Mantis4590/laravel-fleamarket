<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
            $items = Item::where('buyer_id', $user->id)->latest()->get();
        } elseif ($page === 'transaction') {
            $items = Item::query()
                ->whereNotNull('buyer_id') // 取引中
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('buyer_id', $user->id);
                })
                ->orderByDesc('last_message_at')
                ->get();
        } else {
            $items = collect();
        }

        return view('mypage.mypage', compact('user', 'items', 'page'));
    }
}

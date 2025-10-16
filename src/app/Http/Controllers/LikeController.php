<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
class LikeController extends Controller
{
    // いいね機能
    public function store($item_id) {
        Like::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back();
    }

    // いいね解除
    public function destroy($item_id) {
        Like::where('user_id', Auth::id())
        ->where('user_id', Auth::id())
        ->delete();

        return back();
    }
}

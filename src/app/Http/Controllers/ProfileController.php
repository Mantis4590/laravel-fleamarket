<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show() {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    // プロフィール更新処理
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->only(['name', 'postcode', 'address', 'building']);

        // 新しい画像があれば更新
        if ($request->hasFile('image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $data['profile_image'] = $request->file('image')->store('profile_images', 'public');
        }

        $user->update($data);

        // 条件でリダイレクト先を分ける
        if (session('from_register')) {
            session()->forget('from_register');
            return redirect()->route('home')->with('success', 'プロフィールを更新しました');
        }

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました。');
    }
}

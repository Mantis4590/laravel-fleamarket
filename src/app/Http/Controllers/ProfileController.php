<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show() {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request) {
        $user = auth()->user();
        $data = $request->only(['name', 'postcode', 'address', 'building']);

        if ($request->hasFile('image')) {
            // 既存画像があれば削除
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // storage/app/public/avatars に保存 -> DBには相対パスを保存
            $data['profile_image'] = $request->file('image')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'プロフィールを更新しました。');
    }
}

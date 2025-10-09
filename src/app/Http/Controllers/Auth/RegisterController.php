<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    // 会員登録画面の表示
    public function showRegistrationForm() {
        return view('auth.register');
    }

    // 登録処理
    public function register(RegisterRequest $request) {
        // $request->validated() でバリデーション済みデータが取れる
        $validated = $request->validated();

        // ユーザー登録
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ログイン画面の表示
    public function showLoginForm() {
        return view('auth.login');
    }

    // ログイン処理
    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/'); // ログイン成功
        }

        // 入力情報が間違っている場合
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    // ログアウト処理
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

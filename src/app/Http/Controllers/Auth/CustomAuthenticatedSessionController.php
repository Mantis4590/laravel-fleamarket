<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;


class CustomAuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request) {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
            ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません'])
            ->onlyInput('email');
        }

        // メール認証チェック
        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('verification.notice')
                ->with('warning', 'メール認証を完了してください');
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function destroy(Request $request): LogoutResponse {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ログアウト後は商品一覧へ
        return app(LogoutResponse::class);
    }
}

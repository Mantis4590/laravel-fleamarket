<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract {
    public function toResponse($request) {
        // ログアウト後はログイン前の商品一覧ページへ遷移
        return redirect('/guest');
    }
}
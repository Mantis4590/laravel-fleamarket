<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function ログアウトできる() {
        // ユーザーをログイン状態にする
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // ログアウト実行
        $response = $this->post('/logout');

        // ホームに戻る or ログインに戻る
        $response->assertStatus(302);

        // 認証されてないことを確認
        $this->assertGuest();
    }
}
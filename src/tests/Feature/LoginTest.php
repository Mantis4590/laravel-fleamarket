<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function ログインページが表示される() {
        $response = $this->get('/login');

        $response->assertStatus(200)
                ->assertSee('ログイン'); // フォーム内の文字を確認
    }

    /** @test */
    public function 正しい情報ならログインできる() {
        // 事前にユーザー作成（DBに保存される）
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(), // 認証済みでないと弾かれる仕様
        ]);

        // ログイン実行
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // リダイレクト先確認
        $response->assertRedirect('/');

        // 認証されている
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function 間違ったパスワードではログインできない() {
        // ユーザー作成
        $user = User::factory()->create([
            'email' => 'wrong@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 間違った情報でログイン
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        // ログイン失敗
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');

        // 認証されていない
        $this->assertGuest();
    }
}
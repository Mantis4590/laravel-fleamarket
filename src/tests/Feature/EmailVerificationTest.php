<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;

class EmailVerificationTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function 会員登録すると認証メールが送信される()
    {

        Notification::fake();

        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        // Fortify のデフォルトでは認証誘導画面へリダイレクト
        $response->assertRedirect('/email/verify');

        $user = \App\Models\User::where('email', 'test@example.com')->first();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    /** @test */
    public function メール認証リンクを押すとメール認証が行われる() {
        $user = User::factory()->unverified()->create();

        // 署名付きURL（本当のメールの中身と同じ）
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 認証リンクを踏んだ時の挙動
        $response = $this->actingAs($user)->get($verificationUrl);

        // 認証に成功したらプロフィール設定画面へリダイレクト
        $response->assertRedirect('/mypage/profile');
    }

    /** @test */
    public function メール認証完了後プロフィール設定画面を表示できる() {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 認証を完了させる
        $this->actingAs($user)->get($verificationUrl);

        // 認証済みになっていることを確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // 認証完了後、プロフィール設定画面へアクセス
        $response = $this->actingAs($user->fresh())->get('/mypage/profile');

        $response->assertStatus(200);
    }
}
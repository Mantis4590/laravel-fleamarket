<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class UserInfoUpdateTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function プロフィール編集ページに初期値が正しく表示される()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'profile_image' => 'test.png',
            'postcode' => '123-4567',
            'address' => '東京都新宿区テスト1-2-3',
            'building' => 'テストビル101'
        ]);

        // ログインしてプロフィール編集ページへ
        $response = $this->actingAs($user)
            ->get('/mypage/profile');

        $response->assertStatus(200);

        // 各項目の初期値がページに含まれているか
        $response->assertSee('テスト太郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区テスト1-2-3');
        $response->assertSee('テストビル101');

        // プロフィール画像
        $response->assertSee('test.png');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール情報と出品商品一覧が取得できる()
    {
        // 1. ユーザー作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'test.png',
        ]);

        // 2. 出品商品を紐付ける
        $listedItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品した商品A',
        ]);

        // 3. ログイン
        $response = $this->actingAs($user)
            ->get('/mypage?page=sell');

        // 4. 必要な情報が表示されているか確認
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('出品した商品A');
        $response->assertSee($user->profile_image);
    }

    /** @test */
    public function 購入した商品一覧が取得できる()
    {
        $user = User::factory()->create([
            'name' => '購入ユーザー',
        ]);

        // 出品者
        $seller = User::factory()->create();

        // 商品A（ユーザーが購入したことにする）
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入した商品B',
            'buyer_id' => $user->id,   // ← 購入者IDがここなら OK
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入ユーザー');
        $response->assertSee('購入した商品B');
    }

}

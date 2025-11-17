<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細ページで必要な情報が取得できる()
    {
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明文',
            'price' => 5000,
            'condition' => '新品',
            'img_url' => 'test.png',
        ]);

        // カテゴリ
        $category = Category::factory()->create(['name' => '家電']);
        $item->categories()->attach($category->id);

        // コメント
        $commentUser = User::factory()->create();
        Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'すごく良い商品でした！',
        ]);

        // 実行
        $response = $this->get("/item/{$item->id}");

        // 検証
        $response->assertStatus(200);

        // 商品基本情報
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('¥5,000');
        $response->assertSee('新品');
        $response->assertSee('テスト説明文');

        // 画像
        $response->assertSee('test.png');

        // カテゴリ
        $response->assertSee('家電');

        // コメント内容 & コメントしたユーザー名
        $response->assertSee('すごく良い商品でした！');
        $response->assertSee($commentUser->name);
    }
}

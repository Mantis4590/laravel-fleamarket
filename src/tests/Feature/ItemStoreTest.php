<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ItemStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出品商品が正しく保存される()
    {
        // ストレージをフェイクにする
        Storage::fake('public');

        // 1. ユーザー作成
        $user = User::factory()->create();

        // 2. カテゴリを複数作成
        $categories = Category::factory()->count(2)->create();

        // 3. ログインして商品登録POSTを実行
        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これは説明文です',
            'price' => 3000,
            'condition' => '良好',
            'category_ids' => $categories->pluck('id')->toArray(),
            'img_url' => UploadedFile::fake()->create('test.png', 100), // 本来は画像アップロードだがテストでは文字でOK
        ]);

        // リダイレクト成功
        $response->assertStatus(302);

        // DBに保存されたか確認
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これは説明文です',
            'price' => 3000,
            'condition' => '良好',
            'user_id' => $user->id,
        ]);

        // カテゴリの紐付けチェック
        $item = Item::first();
        $this->assertEquals(2, $item->categories()->count());
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        // 準備（Arrange）
        Item::factory()->create(['name' => '赤い靴']);
        Item::factory()->create(['name' => '青い帽子']);
        Item::factory()->create(['name' => '赤いバッグ']);

        // 実行（Act）
        $response = $this->get('/guest?keyword=赤');

        // 検証（Assert）
        $response->assertStatus(200);
        $response->assertSee('赤い靴');
        $response->assertSee('赤いバッグ');
        $response->assertDontSee('青い帽子');
    }

    /** @test */
    public function 検索結果がマイリストタブでも保持されている() {
        // 準備
        $user = User::factory()->create();

        // ユーザーが「赤い靴」をいいね済みをする
        $likeItem = Item::factory()->create(['name' => '赤い靴']);
        $user->likes()->attach($likeItem->id);

        // いいねしてない別アイテム
        Item::factory()->create(['name' => '青い帽子']);

        // ユーザーでログイン
        $this->actingAs($user);

        // 実行：マイリストタブ & keyword=赤 の状態でアクセス
        $response = $this->get('/home?tab=likes&keyword=赤');

        // 検証
        $response->assertStatus(200);

        // 検索キーワード保持
        $this->assertEquals('赤', $response->viewData('keyword'));

        // 検索結果として「赤い靴」だけが出る
        $response->assertSee('赤い靴');
        $response->assertDontSee('青い帽子');
    }
}

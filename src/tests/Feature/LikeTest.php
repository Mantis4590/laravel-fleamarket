<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class LikeTest extends TestCase
{
    /** @test */
    public function いいねを追加できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // いいね追加リクエスト
        $response = $this->post("/item/{$item->id}/like");

        $response->assertStatus(302); // リダイレクトOK

        // likesテーブルに登録されているか
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいね済みの商品ではアイコン状態が変わる() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 事前にいいねしておく
        $user->likes()->attach($item->id);

        $this->actingAs($user);

        // 商品詳細ページ開く
        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);

        // ビューに「いいね済み」を示すクラス等が含まれているか
        $response->assertSee('item-detail__icon--liked');
    }

    /** @test */
    public function いいねを解除できる() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 事前にいいね
        $user->likes()->attach($item->id);

        $this->actingAs($user);

        // いいね解除
        $response = $this->delete("/item/{$item->id}/like");

        $response->assertStatus(302);

        // likesから削除されているか
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}

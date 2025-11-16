<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class MyListIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウト状態ではマイリストは空で表示される()
    {
        // 適当に商品作っておく（ゲストなので mylist は空のはず）
        Item::factory()->count(3)->create();

        $response = $this->get('/home?tab=mylist');

        $response->assertStatus(200);
        $response->assertViewHas('tab', 'mylist');
        $response->assertViewHas('items', function ($items) {
            // ログアウト状態なので何も返らない
            return $items->count() === 0;
        });
    }

    /** @test */
    public function ログインユーザーは自分のいいねした商品だけ取得できる()
    {
        $user      = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = User::factory()->create();

        $likedItem    = Item::factory()->create();
        $notLikedItem = Item::factory()->create();

        // ログインユーザーがいいねした商品
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        // 別ユーザーがいいねした商品
        Like::factory()->create([
            'user_id' => $otherUser->id,
            'item_id' => $notLikedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/home?tab=mylist');

        $response->assertStatus(200);
        $response->assertViewHas('tab', 'mylist');
        $response->assertViewHas('items', function ($items) use ($likedItem) {
            // 自分がいいねした1件だけが返ってくる
            return $items->count() === 1
                && (int) $items->first()->id === (int) $likedItem->id;
        });
    }

    /** @test */
    public function ログインユーザーが何もいいねしていない場合は空が返る()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        // 商品はあるけど、いいねはしてない
        Item::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/home?tab=mylist');

        $response->assertStatus(200);
        $response->assertViewHas('tab', 'mylist');
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 0;
        });
    }
}

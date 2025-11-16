<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ゲストは全商品を取得できる()
    {
        Item::factory()->count(3)->create();

        $response = $this->get('/guest');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return count($items) === 3;
        });
    }

    /** @test */
    public function ログインユーザーは自分の商品を除外して取得できる() {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $othorUser = User::factory()->create();

        // 自分の商品１つ,他人の商品2つ
        Item::factory()->create(['user_id' => $user->id]);
        Item::factory()->create(['user_id' => $othorUser->id]);
        Item::factory()->create(['user_id' => $othorUser->id]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) use ($user) {
            // 取得した商品に"自分の商品が含まれていない"こと
            return $items->every(fn($i) => $i->user_id !== $user->id);
        });
    }

    /** @test */
    public function 購入済み商品はis_soldがtrueとして返ってくる() {
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $otherUser = User::factory()->create();

        $item = Item::factory()->create(['user_id' => $otherUser->id]);

        // 購入済みにする
        $item->buyer_id = $buyer->id;
        $item->save();

        $response = $this->actingAs($buyer)->get('/home');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) use ($buyer) {
            return $items->first()->buyer_id === $buyer->id;
        });
    }
}

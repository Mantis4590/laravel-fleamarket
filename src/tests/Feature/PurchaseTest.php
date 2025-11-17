<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入した商品はSoldと表示される() {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'buyer_id' => $user->id
        ]);

        // Sold表示確認（ビュー状の文字があるかどうか）
        $response = $this->actingAs($user)->get("/item/{$item->id}");
        $response->assertSee('Sold');
    }

    /** @test */
    public function 購入した商品はプロフィールの購入一覧に追加される() {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'buyer_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        // 購入済みの商品が表示されているか確認
        $response->assertSee($item->name);
    }
}

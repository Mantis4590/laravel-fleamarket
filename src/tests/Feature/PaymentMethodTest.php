<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法が小計画面に反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // 支払い方法を選択してpurchase画面にアクセス
        $response = $this->get("/purchase/{$item->id}?payment_method=コンビニ払い");

        // 反映されているか確認
        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');
    }
}

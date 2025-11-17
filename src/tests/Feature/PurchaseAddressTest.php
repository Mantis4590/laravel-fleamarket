<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use Tests\TestCase;

class PurchaseAddressTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
{
    parent::setUp();

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
}

    /** @test */
    public function 送付先住所が購入画面に反映される()
    {
        $user = User::factory()->create([
            'postcode' => '987-6543',
            'address' => '大阪府大阪市2-2-2',
            'building' => 'マンション202',
            'email_verified_at' => now(),
        ]);


        $this->actingAs($user);

        $item = Item::factory()->create();

        // 住所更新
        $this->post("/purchase/address/{$item->id}", [
            'postcode' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // 購入画面を開く
        $response = $this->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-1-1');
        $response->assertSee('テストビル101');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて保存される()
    {
        $user = User::factory()->create([
        'postcode' => '987-6543',
        'address' => '大阪府大阪市2-2-2',
        'building' => 'マンション202',
        'email_verified_at' => now(),
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        // 購入処理
        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
        ]);

        // item の配送先が保存されているか確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'shipping_postcode' => '987-6543',
            'shipping_address' => '大阪府大阪市2-2-2',
            'shipping_building' => 'マンション202',
        ]);
    }
}

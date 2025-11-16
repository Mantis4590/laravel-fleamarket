<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(500, 50000),
            'condition' => '良好',
            'img_url' => 'test.png',
            'buyer_id' => null, // 初期は未購入
        ];
    }
}


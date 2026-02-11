<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = Item::create([
            'user_id' => 1,
            'buyer_id' => 2,
            'name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'images/image_1.jpg',
            'condition' => '良好',
        ]);
        $item->categories()->attach([1, 5]);

        $item = Item::create([
            'user_id' => 2,
            'name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'img_url' => 'images/image_2.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);
        $item->categories()->attach([2,16]);

        $item = Item::create([
            'user_id' => 3,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => null,
            'description' => '新鮮な玉ねぎ３束のセット',
            'img_url' => 'images/image_3.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);
        $item->categories()->attach([6]);

        $item = Item::create([
            'user_id' => 4,
            'name' => '革靴',
            'price' => 4000,
            'brand' => null,
            'description' => 'クラシックなデザインの革靴',
            'img_url' => 'images/image_4.jpg',
            'condition' => '状態が悪い',
        ]);
        $item->categories()->attach([1]);

        $item = Item::create([
            'user_id' => 5,
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => null,
            'description' => '高性能なノートパソコン',
            'img_url' => 'images/image_5.jpg',
            'condition' => '良好',
        ]);
        $item->categories()->attach([2]);

        $item = Item::create([
            'user_id' => 6,
            'name' => 'マイク',
            'price' => 8000,
            'brand' => null,
            'description' => '高音質のレコーディング用マイク',
            'img_url' => 'images/image_6.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);
        $item->categories()->attach([16]);

        $item = Item::create([
            'user_id' => 7,
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'img_url' => 'images/image_7.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);
        $item->categories()->attach([1]);

        $item = Item::create([
            'user_id' => 8,
            'name' => 'タンブラー',
            'price' => 500,
            'brand' => null,
            'description' => '使いやすいタンブラー',
            'img_url' => 'images/image_8.jpg',
            'condition' => '状態が悪い',
        ]);
        $item->categories()->attach([2, 12]);

        $item = Item::create([
            'user_id' => 9,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'img_url' => 'images/image_9.jpg',
            'condition' => '良好',
        ]);
        $item->categories()->attach([2, 12]);

        $item = Item::create([
            'user_id' => 10,
            'name' => 'メイクセット',
            'price' => 2500,
            'brand' => null,
            'description' => '便利なメイクアップセット',
            'img_url' => 'images/image_10.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);
        $item->categories()->attach([7, 9]);
    }
}

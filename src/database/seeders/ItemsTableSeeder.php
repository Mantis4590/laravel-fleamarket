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
        Item::create([
            'name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'images/image_1.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'img_url' => 'images/image_2.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);

        Item::create([
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'null',
            'description' => '新鮮な玉ねぎ３束のセット',
            'img_url' => 'images/image_3.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);

        Item::create([
            'name' => '革靴',
            'price' => 4000,
            'brand' => 'null',
            'description' => 'クラシックなデザインの革靴',
            'img_url' => 'images/image_4.jpg',
            'condition' => '状態が悪い',
        ]);

        Item::create([
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => 'null',
            'description' => '高性能なノートパソコン',
            'img_url' => 'images/image_5.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'name' => 'マイク',
            'price' => 8000,
            'brand' => 'null',
            'description' => '高音質のレコーディング用マイク',
            'img_url' => 'images/image_6.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);

        Item::create([
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => 'null',
            'description' => 'おしゃれなショルダーバッグ',
            'img_url' => 'images/image_7.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);

        Item::create([
            'name' => 'タンブラー',
            'price' => 500,
            'brand' => 'null',
            'description' => '使いやすいタンブラー',
            'img_url' => 'images/image_8.jpg',
            'condition' => '状態が悪い',
        ]);

        Item::create([
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'img_url' => 'images/image_9.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'name' => 'メイクセット',
            'price' => 2500,
            'brand' => 'null',
            'description' => '便利なメイクアップセット',
            'img_url' => 'images/image_10.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);
    }
}

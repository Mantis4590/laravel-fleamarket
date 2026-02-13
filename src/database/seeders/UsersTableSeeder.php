<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // ① C001〜C005 出品者
        User::updateOrCreate(
            ['email' => 'seller_a@example.com'],
            ['name' => '出品者A', 'password' => Hash::make('password')]
        );

        // ② C006〜C010 出品者
        User::updateOrCreate(
            ['email' => 'seller_b@example.com'],
            ['name' => '出品者B', 'password' => Hash::make('password')]
        );

        // ③ 何も紐づかないユーザー
        User::updateOrCreate(
            ['email' => 'no_relation@example.com'],
            ['name' => '未紐付けユーザー', 'password' => Hash::make('password')]
        );
    }
}

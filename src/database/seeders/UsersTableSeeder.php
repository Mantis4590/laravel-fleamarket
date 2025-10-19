<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // ダミーユーザーを5人作成
        User::create([
            'name' => '山田太郎',
            'email' => 'taro@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '佐藤花子',
            'email' => 'hanako@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '田中一郎',
            'email' => 'ichiro@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '高橋美咲',
            'email' => 'misaki@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '鈴木健太',
            'email' => 'kenta@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '中村優',
            'email' => 'yuu@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '小林直樹',
            'email' => 'naoki@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '加藤彩',
            'email' => 'aya@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '松本翔',
            'email' => 'sho@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => '井上真理',
            'email' => 'mari@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}

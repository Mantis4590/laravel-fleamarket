<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegisterTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function 登録ページが表示される() {
        $this->get('/register')
            ->assertStatus(200)
            ->assertSee('会員登録');
    }

    /** @test */
    public function 正しい情報なら登録できる() {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $response->assertRedirect('/email/verify');
    }

    /** @test */
    public function すでに使われているメールは弾かれる() {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
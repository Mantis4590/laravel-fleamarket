<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => '良い商品でした！'
        ]);

        // コメントが保存されている
        $this->assertEquals(1, Comment::count());

        // リダイレクト成功
        $response->assertStatus(302);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない() {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => '未ログインコメント'
        ]);

        // 保存されない
        $this->assertDatabaseCount('comments', 0);

        // ログインページへリダイレクト
        $response->assertRedirect('/login');
    }

    /** @test */
    public function コメントが未入力の場合バリデーションメッセージが表示される() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->from("/item/{item->id}")
            ->post("/item/{$item->id}/comment", [
                'comment' => ''
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['comment']);
    }

    /** @test */
    public function コメントが255文字以上の場合バリデーションエラー() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longText = str_repeat('あ', 256);

        $response = $this->from("/item/{$item->id}")
            ->post("/item/{$item->id}/comment", [
                'comment' => $longText
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['comment']);
    }
}

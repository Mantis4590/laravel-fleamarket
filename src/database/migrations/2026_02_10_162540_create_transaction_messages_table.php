<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_messages', function (Blueprint $table) {
            $table->id();

            // 取引スレッド = itemsなのでitem_idを持つ
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');

            // 送信者
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // 本文 or 画像
            $table->text('body')->nullable();
            $table->string('image_path')->nullable();

            $table->timestamps();

            // よく使うので牽引
            $table->index(['item_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_messages');
    }
}

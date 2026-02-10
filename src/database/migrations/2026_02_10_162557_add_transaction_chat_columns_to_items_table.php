<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionChatColumnsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // 取引一覧の並び替え用（最新メッセージ時刻）
            $table->timestamp('last_message_at')->nullable()->after('buyer_id');

            // 既読管理（購入者/出品者それぞれ）
            $table->timestamp('buyer_last_read_at')->nullable()->after('last_message_at');
            $table->timestamp('seller_last_read_at')->nullable()->after('buyer_last_read_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'last_message_at',
                'buyer_last_read_at',
                'seller_last_read_at',
            ]);
        });
    }
};
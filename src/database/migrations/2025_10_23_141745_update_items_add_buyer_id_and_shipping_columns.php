<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItemsAddBuyerIdAndShippingColumns extends Migration


{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');

        // ここに shipping 系も追加
        $table->string('shipping_postcode')->nullable();
        $table->string('shipping_address')->nullable();
        $table->string('shipping_building')->nullable();
    });
}

public function down()
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropForeign(['buyer_id']);
        $table->dropColumn('buyer_id');

        $table->dropColumn('shipping_postcode');
        $table->dropColumn('shipping_address');
        $table->dropColumn('shipping_building');
    });
}

}
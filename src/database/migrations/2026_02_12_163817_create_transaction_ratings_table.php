<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rater_id')->constrained('users')->cascadeOnDelete(); // 評価する側
            $table->foreignId('ratee_id')->constrained('users')->cascadeOnDelete(); // 評価される側
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->timestamps();

            $table->unique(['item_id', 'rater_id']); // ✅ 取引につき同じ人は1回だけ
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_ratings');
    }
};

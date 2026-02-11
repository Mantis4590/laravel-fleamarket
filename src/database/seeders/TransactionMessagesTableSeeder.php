<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\TransactionMessage;

class TransactionMessagesTableSeeder extends Seeder
{
    public function run(): void
    {
        // 取引中の「腕時計」を対象にする（id=1想定。安全にnameで取る）
        $item = Item::where('name', '腕時計')->first();

        if (!$item || !$item->buyer_id) {
            return;
        }

        // 出品者(=user_id) → 購入者
        TransactionMessage::create([
            'item_id' => $item->id,
            'sender_id' => $item->user_id,
            'body' => 'ご購入ありがとうございます！発送準備しますね。',
            'image_path' => null,
        ]);

        // 購入者 → 出品者
        TransactionMessage::create([
            'item_id' => $item->id,
            'sender_id' => $item->buyer_id,
            'body' => 'よろしくお願いします！',
            'image_path' => null,
        ]);

        // 最新メッセージ時刻を items に反映（FN004用）
        $item->update([
            'last_message_at' => now(),
        ]);
    }
}

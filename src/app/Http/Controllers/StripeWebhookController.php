<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request) {
        $payload = $request->getContent();
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $exception) {
            Log::error('Stripe Webhook エラー: ' . $exception->getMessage());
            return response('Invalid signature', 400);
        }

        // イベントの種類によって処理分岐
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // メタデータにitem_id が含まれている場合のみ処理
            $itemId = $session->metadata->item_id ?? null;
            $userId = $session->metadata->user_id ?? null;

            if ($itemId && $userId) {
                $item = Item::find($itemId);

                if ($item && !$item->buyer_id) {
                    $item->buyer_id = $userId;
                    $item->save();

                    Log::info("商品ID {$itemId} が購入されました (buyer_id={$userId}) ");
                }
            }
        }

        return response('Webhook handled', 200);

    }
}

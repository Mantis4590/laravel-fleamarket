<?php

namespace App\Mail;

use App\Models\Item;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Item $item,
        public User $buyer,
        public User $seller,
    ) {}

    public function build()
    {
        return $this
            ->subject('取引完了のお知らせ')
            ->view('emails.transaction_completed');
    }
}

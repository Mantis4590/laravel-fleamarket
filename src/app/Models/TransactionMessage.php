<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionMessage extends Model
{
    protected $fillable = [
        'item_id',
        'sender_id',
        'body',
        'image_path',
    ];

    public function item(): BelongsTo
    {
    return $this->belongsTo(Item::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

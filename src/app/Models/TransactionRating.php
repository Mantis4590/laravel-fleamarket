<?php

// app/Models/TransactionRating.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRating extends Model
{
    protected $fillable = [
        'item_id', 'rater_id', 'ratee_id', 'rating',
    ];
}

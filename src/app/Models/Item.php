<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'brand',
        'description',
        'img_url',
        'condition',
    ];

    public function likedByUsers() {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
}

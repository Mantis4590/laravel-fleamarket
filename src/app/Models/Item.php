<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'user_id',
        'buyer_id',
        'shipping_postcode',
        'shipping_address',
        'shipping_building',
    ];

    public function likedByUsers() {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
    
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function categories()
{
    return $this->belongsToMany(Category::class, 'category_item');
}
    public function transactionMessages(): HasMany
    {
        return $this->hasMany(\App\Models\TransactionMessage::class)->latest();
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

}

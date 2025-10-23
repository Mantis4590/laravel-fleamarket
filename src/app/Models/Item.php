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
        'user_id',
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

}

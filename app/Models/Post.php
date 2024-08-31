<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $guarded = false;

    protected $with = ['image'];

    public function getDataAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function likesPosts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'liked_posts', 'post_id', 'user_id');
    }

    public function image(): HasOne
    {
        return $this->hasOne(PostImage::class, 'post_id', 'id')
            ->where('is_active', true);
    }
}

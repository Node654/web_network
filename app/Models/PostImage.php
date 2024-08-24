<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;

    protected $table = 'post_images';

    protected $guarded = false;

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function getUrlAttribute()
    {
        return url('storage/'.$this->path);
    }

    public static function clearStorage()
    {
        $images = PostImage::where('user_id', auth()->id())->where('is_active', false)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostImage\StoreRequest;
use App\Http\Resources\PostImage\PostImageResource;
use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;

class PostImageController extends Controller
{
    public function store(StoreRequest $storeRequest)
    {
        $path = Storage::disk('public')->put('/images', $storeRequest->validated()['image']);
        $image = PostImage::create([
            'path' => $path,
            'user_id' => auth()->id(),
        ]);
        return new PostImageResource($image);
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class PostController extends Controller
{
    public function store(StoreRequest $storeRequest)
    {
        try {
            DB::beginTransaction();
            $data = $storeRequest->validated();
            $imageId = $storeRequest->get('image_id');
            unset($data['image_id']);
            $data['user_id'] = auth()->id();
            $post = Post::create($data);
            $this->associateImageWithPost($post->id, $imageId);
            PostImage::clearStorage();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }

        return new PostResource($post);
    }

    private function associateImageWithPost(int $postId, int $imageId): void
    {
        if (isset($imageId)) {
            PostImage::where('id', $imageId)->update([
                'post_id' => $postId,
                'is_active' => true
            ]);
        }

    }
}

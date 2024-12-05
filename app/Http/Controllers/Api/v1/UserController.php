<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StatRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\StatResource;
use App\Http\Resources\User\UserResource;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNot('id', auth()->id())->latest()->get();
        $followings = SubscriberFollowing::where('subscriber_id', auth()->id())->get('following_id')->pluck('following_id')->toArray();
        foreach ($users as $user)
        {
            if (in_array($user->id, $followings)) {
                $user->is_followed = true;
            }
        }
        return UserResource::collection($users);
    }

    public function posts(User $user)
    {
        $posts = $user->posts()->withCount('reposted')->latest()->get();
        $likedPosts = LikedPost::where('user_id', auth()->id())->get('post_id')->pluck('post_id')->toArray();
        foreach ($posts as $post) {
            if (in_array($post->id, $likedPosts)) {
                $post->is_liked = true;
            }
        }

        return PostResource::collection($posts);
    }

    public function toggleFollowing(User $user)
    {
        $isSubscriber = auth()->user()->followings()->toggle($user->id);
        $user->is_followed = count($isSubscriber['attached']) > 0;
        return UserResource::make($user);
    }

    public function followingPosts()
    {
        $followedIds = auth()->user()->followings()->get(['following_id'])->pluck('following_id')->toArray();
        $likedPosts = LikedPost::where('user_id', auth()->id())->get('post_id')->pluck('post_id')->toArray();
        $posts = Post::whereIn('user_id', $followedIds)->whereNotIn('id', $likedPosts)->get();
        foreach ($posts as $post) {
            if (in_array($post->id, $likedPosts)) {
                $post->is_liked = true;
            }
        }
        return PostResource::collection($posts);
    }

    public function stat(StatRequest $statRequest)
    {
        $data = $statRequest->validated();
        $userId = $data['user_id'] ?? auth()->id();
        $result = [];
        $result['subscribers_count'] = SubscriberFollowing::where('following_id', $userId)->count();
        $result['followings_count'] = SubscriberFollowing::where('subscriber_id', $userId)->count();
        $postsId = Post::where('user_id', $userId)->get('id')->pluck('id')->toArray();
        $result['likes_count'] = LikedPost::whereIn('post_id', $postsId)->count();
        $result['posts_count'] = count($postsId);
        return new StatResource($result);
    }
}

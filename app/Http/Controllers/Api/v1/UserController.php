<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\LikedPost;
use App\Models\SubscriberFollowing;
use App\Models\User;
use Illuminate\Http\Request;

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
        $posts = $user->posts;
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
}

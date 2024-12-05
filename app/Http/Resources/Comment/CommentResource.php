<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $nameAnsweredUser = $this->parent ? $this->parent->user->name : null;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => new UserResource($this->user),
            'answered_for_user' => $nameAnsweredUser,
            'date' => $this->date
        ];
    }
}

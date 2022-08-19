<?php

namespace App\Http\Resources\v1\User;

use App\Http\Resources\V1\Post\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'fullname' => $this->name,
            'username' => $this->username,
            'post_amount' => $this->posts,
            'follower_amount' => $this->followers,
            'following_amount' => $this->followings,
            'bio' => $this->bio,
            'posts' => PostResource::collection($this->user_posts),
        ];
    }
}

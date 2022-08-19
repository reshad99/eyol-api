<?php

namespace App\Http\Resources\V1\Post;

use App\Http\Resources\V1\Comment\CommentResource;
use App\Http\Resources\V1\Gallery\GalleryResource;
use App\Http\Resources\V1\User\UserPostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'post_id'    => $this->id,
            'post_caption'    => $this->caption,
            'like_amount'       => $this->like_amount,
            'comment_amount'       => $this->comment_amount,
            'post_comments' => CommentResource::collection($this->comments) ,
            'post_writer'       => new UserPostResource($this->whenLoaded('user')),
            'post_gallery'    => GalleryResource::collection($this->gallery),
        ];

        return $data;
    }
}

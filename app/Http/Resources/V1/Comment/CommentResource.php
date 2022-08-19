<?php

namespace App\Http\Resources\V1\Comment;

use App\Http\Resources\v1\User\UserCommentResource;
use App\Http\Resources\V1\User\UserPostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment_id' => $this->id,
            'comment_text' => $this->comment,
            'comment_like_amount' => $this->like_amount,
            'comment_writer' => new UserCommentResource($this->user),
            'reply_count' => $this->reply_count(),
        ];

        return $data;
    }
}

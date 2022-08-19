<?php

namespace App\Http\Resources\v1\Reply;

use App\Http\Resources\V1\User\UserPostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'reply_id' => $this->id,
            'reply_text' => $this->text,
            'reply_like_amount' => $this->like_amount,
            'reply_writer' => new UserPostResource($this->user),
        ];
    }
}

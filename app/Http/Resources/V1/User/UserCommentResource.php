<?php

namespace App\Http\Resources\v1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCommentResource extends JsonResource
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
            'id'   => $this->id,
            'username'  => $this->username,
            'photo'     => $this->profile_photo,
            'verified'  => $this->when($this->verified == 0, false, true),
            'has_story' => $this->when($this->active_story_count() > 0, true, false),
        ];
    }
}

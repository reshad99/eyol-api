<?php

namespace App\Http\Resources\v1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
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
            'id'   => $this->id,
            'username'  => $this->username,
            'photo'     => $this->profile_photo,
            'verified'  => $this->verified,
            'has_story' => $this->when($this->active_story_count() > 0, true, false),
        ];

        $data['is_following'] = $this->is_following_this($this->id);

        return $data;
    }
}

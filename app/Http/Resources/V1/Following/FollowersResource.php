<?php

namespace App\Http\Resources\V1\Following;

use App\Models\Following;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FollowersResource extends JsonResource
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
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_username' => $this->follower->username,
            'follower_photo' => Storage::disk('public')->url($this->follower->photo),
            'follower_verified' => $this->follower->verified,
        ];

        $data['is_following'] = $this->is_following_this($this->follower->id);


        return $data;
    }
}

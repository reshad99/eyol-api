<?php

namespace App\Http\Resources\V1\Following;

use App\Http\Resources\V1\User\UserPostResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FollowingResource extends JsonResource
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
            'following_id' => $this->following->id,
            'following_name' => $this->following->name,
            'following_username' => $this->following->username,
            'following_photo' => Storage::disk('public')->url($this->following->photo),
            'following_verified' => $this->following->verified,
        ];



        return $data;
    }
}

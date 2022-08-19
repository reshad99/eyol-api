<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResource extends JsonResource
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
            'user_id'        => $this->id,
            'user_name'      => $this->name,
            'user_username'  => $this->username,
            'user_photo'     => $this->profile_photo,
            'user_interests' => new UserInterestResource($this->interests),
            'user_looking_for' => new UserLookingForResource($this->interests),
        ];
    }
}

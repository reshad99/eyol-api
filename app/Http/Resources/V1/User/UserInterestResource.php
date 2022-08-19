<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInterestResource extends JsonResource
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
            'love'       => $this->love,
            'fling'      => $this->fling,
            'fun_chats'  => $this->fun_chats,
            'friends'    => $this->friends,
            'job'        => $this->job,
            'networking' => $this->networking,
        ];
    }
}

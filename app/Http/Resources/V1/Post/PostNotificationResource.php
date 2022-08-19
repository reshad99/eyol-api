<?php

namespace App\Http\Resources\v1\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class PostNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //Notificationda postun axrinci seklinin yuklenmesi
        return [
            'id' => $this->id,
            'photo' => $this->photo,
        ];
    }
}

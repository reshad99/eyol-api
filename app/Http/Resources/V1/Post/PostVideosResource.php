<?php

namespace App\Http\Resources\v1\post;

use Illuminate\Http\Resources\Json\JsonResource;

class PostVideosResource extends JsonResource
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
            'video' => VideoResource::collection($this->videos)
        ];
    }
}

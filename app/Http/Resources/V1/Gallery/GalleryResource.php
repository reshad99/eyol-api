<?php

namespace App\Http\Resources\V1\Gallery;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GalleryResource extends JsonResource
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
            'media_url'  => $this->url,
            'media_type' => $this->media_type,
            'media_thumbnail' => $this->thumbnail,
        ];
    }
}

<?php

namespace App\Http\Resources\V1\Story;

use App\Models\StoryView;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StoryIndexResource extends JsonResource
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
            'story_id' => $this->id,
            'story_media' => $this->media,
            'story_views' => $this->lookings,
            'has_shown' => $this->when(StoryView::where('user_id', Auth::guard('api')->user()->id)->where('story_id', $this->id)->exists(), true, false),
        ];
    }
}

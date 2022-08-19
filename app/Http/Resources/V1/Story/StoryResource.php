<?php

namespace App\Http\Resources\v1\Story;

use App\Http\Resources\v1\user\UserStoryResource;
use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class StoryResource extends JsonResource
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
            'story_publisher' => new UserStoryResource($this->user),
        ];

        $story_array = array();
        $view_array = array();

        $publisher_id = $this->user->id;
        $stories = Story::where('user_id', $publisher_id)->where('active', true)->select('id')->get();
        foreach ($stories as $s)
        {
            $story_array[] = $s->id;
        }

        $story_views = StoryView::where('user_id', Auth::user()->id)->select('story_id')->get();
        foreach ($story_views as $s)
        {
            $view_array[] = $s->story_id;
        }
        if(array_diff($story_array, $view_array))
        $data['has_shown_all'] = false;
        else
        $data['has_shown_all'] = true;

        return $data;
    }
}

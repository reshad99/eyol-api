<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\ResultType;
use App\Http\Requests\v1\Story\StoryRequest;
use App\Http\Resources\V1\Story\StoryIndexResource;
use App\Http\Resources\v1\Story\StoryResource;
use App\Models\Story;
use App\Models\User;
use App\Models\StoryView;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $limit = $request->has('limit') ? $request->limit : 20;
        // $story = Story::query();

        // $story->whereIn('user_id', $this->is_following());
        // $story->whereNotIn('user_id', $this->is_blocked());
        // $story->orWhere('user_id', $this->auth_id());

        // if($request->has('sortBy'))
        // $story->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        // else
        // $story->orderBy('id', 'DESC');

        // $data = $story->paginate($limit);
        // return $this->apiResponse(ResultType::Fetch, StoryResource::collection($data), 200, $data->lastPage());
    }

    public function user_stories($id)
    {
        try {
            User::findOrFail($id);
            $stories = Story::where('user_id', $id)->where('active', true)->get();
            return $this->apiResponse(ResultType::Fetch, StoryIndexResource::collection($stories), 200);

        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoryRequest $request)
    {
        $media = $request->media;
        $user_id = $this->auth_id();

        $media_name = uniqid().'.'.$media->getCLientOriginalExtension();
        $media->storeAs('images/stories/', $media_name, 'public');

        $story = new Story;
        $story->media = $media_name;
        $story->user_id = $user_id;
        $story->save();

        return $this->apiResponse(ResultType::Create, null, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $story = Story::findOrFail($id);
            if (StoryView::where('story_id', $id)->where('user_id', $this->auth_id())->exists())
            return false;

            $storyview = new StoryView;
            $storyview->story_id = $id;
            $storyview->user_id = $this->auth_id();
            $storyview->save();
            $story->lookings += 1;
            $story->update();
        }
        catch (\Exception $e)
        {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function group_by(Request $request)
    {
        //baxilan storylerin axira dusmesi
        try {
            $limit = $request->has('limit') ? $request->limit : 20;
            $story = Story::query();

            $story->where('active', true);
            $story->orWhere('user_id', $this->auth_id());
            $story->whereIn('user_id', $this->is_following());
            $story->whereNotIn('user_id', $this->is_blocked());


            if($request->has('sortBy'))
            $story->orderBy($request->sortBy, $request->query('sort', 'DESC'));
            else
            $story->orderBy('id', 'DESC');

            $story->groupBy('user_id');

            $data = $story->paginate($limit);
            return $this->apiResponse(ResultType::Fetch, StoryResource::collection($data), 200, $data->lastPage());
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}

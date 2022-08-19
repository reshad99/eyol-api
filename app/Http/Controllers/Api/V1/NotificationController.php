<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\v1\Notifications\NotificationResource;
use App\Http\Resources\V1\User\UserPostResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $notifications = Notification::query();

        $notifications->where('auth_id', $this->auth_id());

        if($request->has('sortBy'))
        $notifications->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $notifications->orderBy('id', 'DESC');

        $data = $notifications->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, NotificationResource::collection($data), 200, $data->lastPage());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function mention()
    {
        
    }
}

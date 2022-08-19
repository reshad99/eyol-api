<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\V1\Following\FollowersResource;
use App\Http\Resources\V1\Following\FollowingResource;
use App\Models\Following;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class FollowController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
    * Display followers list of user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function followers(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $followers = Following::query();

        $followers->where('followed_id', $this->auth_id())->where('accept', true);

        if($request->has('sortBy'))
        $followers->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $followers->orderBy('id', 'DESC');

        if($request->has('q'))
        $followers->where('username', 'like', '%'.$request->q.'%')->orWhere('name', 'like', '%'.$request->q.'%');

        $followers->select('follower_id');

        $data = $followers->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, FollowersResource::collection($data), 200, $data->lastPage());
    }

    /**
    * Display followers list of user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function followings(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $followings = Following::query();

        $followings->where('follower_id', $this->auth_id())->where('accept', true);

        if($request->has('sortBy'))
        $followings->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $followings->orderBy('id', 'DESC');

        if($request->has('q'))
        $followings->where('username', 'like', '%'.$request->q.'%')->orWhere('name', 'like', '%'.$request->q.'%');

        $followings->select('followed_id');

        $data = $followings->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, FollowingResource::collection($data), 200, $data->lastPage());
    }

    public function follow($id)
    {
        try
        {
            $user = User::findOrFail($id);
            if(Following::where('follower_id', $this->auth_id())->where('followed_id', $id)->first())
            return response(['success' => false, 'message' => 'You are already following this user.']);
            elseif($id == $this->auth_id())
            return response(['success' => false, 'message' => 'You can not follow yourself.']);

            if (in_array($id, $this->is_blocked()))
            return response(['success' => false, 'message' => 'You can not follow this user. This user is blocked']);

            $follow = new Following;
            $follow->follower_id = $this->auth_id();
            $follow->followed_id = $id;

            if ($user->hidden_profile == true)
            {
                $follow->accept = false;
                $this->notification_save('followRequest', $id);
                return response(['success' => true, 'message' => 'Follow request sended']);
            }
            else
            {
                $this->notification_save('follow', $id);
                $user->followers += 1;
                $user->update();
                $auth_user = User::find($this->auth_id());
                $auth_user->followings += 1;
                $auth_user->update();
                return response(['success' => true, 'message' => 'User followed']);
            }

            $follow->save();

        }
        catch (\Exception $e)
        {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function unfollow($id)
    {
        try
        {
            $user = User::findOrFail($id);
            $follow = Following::where('follower_id', $this->auth_id())->where('followed_id', $id)->first();
            if ($follow)
            $follow->delete();
            else
            return response(['success' => false, 'message' => 'You are not following this user.']);

            $user->followers -= 1;
            $user->update();

            $auth_user = User::find($this->auth_id());
            $auth_user->followings -= 1;
            $auth_user->update();
            return response(['success' => true, 'message' => 'User unfollowed']);
        }
        catch (\Exception $e)
        {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function follow_requests(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $requests = Following::query();

        $requests->where('followed_id', $this->auth_id())->where('accept', false)->get();

        if($request->has('sortBy'))
        $requests->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $requests->orderBy('id', 'DESC');

        $data = $requests->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, FollowersResource::collection($data), 200, $data->lastPage());
    }

    public function accept_request($id)
    {
        $follow = Following::find($id);
        $follow->accept = true;
        $follow->update();

        $follower_id = $follow->follower_id;
        $followed_id = $follow->followed_id;

        $follower = User::find($follower_id);
        $follower->followings += 1;
        $follower->update();

        $followed = User::find($followed_id);
        $followed->followers += 1;
        $followed->update();

        return response(['success' => true, 'message' => 'Follow request accepted'], 200);
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
}

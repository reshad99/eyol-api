<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Resources\V1\User\UserShowResource;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except("store");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 20;

        $users = User::query();

        if ($request->has('q'))
        $users->where('name', 'like', '%' .$request->q.'%');

        if($request->has('sortBy'))
        $users->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        $data = $users->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, UserShowResource::collection($data), 200, $data->lastPage());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $api_token = Str::random(60);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->api_token = hash('md5', $api_token);
        $user->remember_token = Str::random(10);
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['data' => $user, 'message' => 'New User has been created', 'Token' => $api_token], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user)
        return response()->json($user, 200);
        else
        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        return response(['data' => $user, 'message' => 'User has been updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user)
        {
            $user->delete();
            return response(['message' => 'User has been deleted'], 200);
        }
        else
        {
            return response(['message' => 'User not found'], 404);
        }
    }
}

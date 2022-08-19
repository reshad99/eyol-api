<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\ResultType;
use App\Http\Requests\v1\Profile\ProfileUpdateRequest;
use App\Http\Resources\v1\User\UserProfileResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show($id)
    {
        try
        {
            $user = User::findOrFail($id);
            return $this->apiResponse(ResultType::Fetch, new UserProfileResource($user), 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = User::find($this->auth_id());
        $user->username = $request->username;
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->category_id = $request->category_id;
        $user->update();

        return $this->apiResponse(ResultType::Update, null, 200);
    }

    public function videos($id)
    {
        $posts = Post::where('user_id', $id)->get();
    }
}

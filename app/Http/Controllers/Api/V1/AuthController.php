<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\Hobby;
use App\Models\SelectedHobby;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $phone = $request->phone;
        $password = $request->password;
        $user = User::where('phone', $phone)->first();
        if ($user)
        {
            if (Hash::check($password, $user->password))
            {
                $token = Str::random(60);
                $user->api_token = $token;
                $user->update();
                return response()->json([
                    'name' => $user->name,
                    'access_token' => $token,
                    'time' => time(),
                ]);
            }
            else
            return response()->json(['success' => false, 'message' => 'Şifrə səhvdir']);
        }
        else
        return response()->json(['success' => false, 'message' => 'Nömrə yanlışdır']);
    }

    public function register(RegisterRequest $request)
    {

        try {
            $user = new User;
            $user->phone = $request->phone;
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->username = $request->username;
            $user->dob = $request->dob;
            $user->gender = $request->gender;
            $user->height = $request->height;
            $user->interest = $request->interest;
            $user->zodiac = $request->zodiac;
            $user->alcohol = $request->alcohol;
            $user->save();

            $id = $user->id;
            $hobby = new Hobby;
            $hobby->gym = $request->gym;
            $hobby->art = $request->art;
            $hobby->gaming = $request->gaming;
            $hobby->sport = $request->sport;
            $hobby->book = $request->book;
            $hobby->cinema = $request->cinema;
            $hobby->user_id = $id;
            $hobby->save();

            $interest = new UserInterest;
            $interest->user_id = $id;
            $interest->love = $request->love;
            $interest->fling = $request->fling;
            $interest->fun_chats = $request->fun_chats;
            $interest->friends = $request->friends;
            $interest->job = $request->job;
            $interest->networking = $request->networking;
            $interest->relationship = $request->relationship;
            $interest->save();
            return response(['success' => true, 'message' => 'User has been registered']);

        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()]);
        }

    }
}

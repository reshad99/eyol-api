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
        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();
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
            return response()->json(['success' => false, 'message' => 'Email və ya şifrə yanlışdır'], 401);
        }
        else
        return response()->json(['success' => false, 'message' => 'Email və ya şifrə yanlışdır'], 401);
    }

    public function register(RegisterRequest $request)
    {

        try {
            $user = new User;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->username = $request->username;
            $user->dob = $request->dob;
            $user->gender = $request->gender;
            $user->save();
            return response(['success' => true, 'message' => 'User has been registered', 'data' => $user]);

        } catch (\Exception $e) {
            return response(['success' => false, 'message' => 'Əməliyyatda səhv aşkarlandı']);
        }

    }
}

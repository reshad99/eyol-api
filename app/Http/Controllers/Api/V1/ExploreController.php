<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\ResultType;
use App\Http\Resources\v1\Explore\ExploreResource;
use App\Models\User;
use App\Models\UserInterest;
use App\Models\UserLookingFor;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function explore(Request $request)
    {
        //explore bitir

        $limit = $request->has('limit') ? $request->limit : 20;
        $gender = $request->gender;
        $age1 = $request->age1;
        $age2 = $request->age2;
        $height1 = $request->height1;
        $height2 = $request->height2;
        $looking_fors = $request->looking_for;
        $users = array();



        $date1 = date('Y') - $age1.'-01-01';
        $date2 = date('Y') - $age2.'-01-01';
        // return $date2;
        // $interest = $request->interest;

        $user = User::query();

        if ($gender != 'both')
        $user->where('gender', $gender);

        if ($age1 && $age2)
        $user->whereBetween('dob', [$date2, $date1]);

        if ($height1 && $height2)
        $user->whereBetween('height', [$height1, $height2]);

        foreach ($looking_fors as $l)
        {
            $interest_query = UserInterest::query();
            if ($l == 'relationship')
            {
                $interest_query->where('love', true)->orWhere('fling', true);
            }

            if ($l == 'date')
            {
                $interest_query->where('networking', true);
            }

            if ($l == 'fun')
            {
                $interest_query->where('fun_chats', true)->where('friends', true);
            }

            if ($l == 'coffee')
            {
                $interest_query->where('job', true);
            }

        }

        $interested_users = $interest_query->get();

        foreach ($interested_users as $u)
        {
            $users[] = $u->user_id;
        }

        if ($request->has('looking_for'))
        $user->whereIn('id', $users);

        $data = $user->paginate($limit);

        return $this->apiResponse(ResultType::Fetch, ExploreResource::collection($data), 200, $data->lastPage());
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Models\Blocking;
use App\Models\Following;
use Illuminate\Http\Request;

class BlockController extends Controller
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

    public function block_unblock($id)
    {
        $auth_is_blocker = Blocking::where('blocker_id', $this->auth_id())->where('blocked_id', $id)->first();
        $auth_is_blocked = Blocking::where('blocker_id', $id)->where('blocked_id', $this->auth_id())->first();
        if ($auth_is_blocker)
        {
            $id = $auth_is_blocker->id;
            $block = Blocking::find($id);
            $block->delete();
            return response(['success' => true, 'message' => 'User Unblocked'], 200);
        }
        elseif ($auth_is_blocked)
        {
            return response(['success' => false, 'message' => 'Restricted operation. You have been blocked by this user.'], 400);
        }

        $block = new Blocking;
        $block->blocker_id = $this->auth_id();
        $block->blocked_id = $id;
        $block->save();

        Following::where('follower_id', $this->auth_id())->where('followed_id', $id)->delete();
        Following::where('follower_id', $id)->where('followed_id', $this->auth_id())->delete();
        
        return response(['success' => true, 'message' => 'User Blocked'], 200);
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

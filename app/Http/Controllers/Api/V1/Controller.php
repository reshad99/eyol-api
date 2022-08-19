<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Blocking;
use App\Models\Following;
use App\Models\Notification;
use App\Models\Post;
use App\Models\StoryView;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{

    /**



    */



    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Retrieve Authorized User ID.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendNotification($type, $action_id, $object_id){

    }

    public function notification_save($type, $action_id, $object_id = 0)
    {
        $token = "cXrhDs6loaE:APA91bEk33-3pFlTsjqTNg52m8HFJa_hYK-jFke3WFuxfsoJVXRhmhqx7xJ-gUyqTj5nWRztiKywm_uhbhwZm8m0JAkGrxEKN41U1WJrNsB1_InXDJyzhsNtECun6vspc1qfn5WK6QGU";
        $from = "AAAATc7HJxg:APA91bGkOch4zi8YMhi5VDddlXT8CR5Lj3MzCOlRmG7KH9JY1cGM1ZPU8ohIrB0GjzEEItb1bqvsUtAOb7ET2tgll0i5YJkolWuJOcJV8N35h3etB4T9I1OXr6t6LKsNAwcJjkf_Pb8H";
        $msg = array
              (
                // 'body'  => "Testing Testing",
                // 'title' => "Hi, From Raj",
                // 'receiver' => 'erw',
                // 'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
                // 'sound' => 'mySound'/*Default sound*/
                'type' => $type,
                'action_id' => $type,
                'object_id' => $object_id,
                'auth_id' => $this->auth_id()
              );

        $fields = array
                (
                    'to'        => $token,
                    'notification'  => $msg
                );

        $headers = array
                (
                    'Authorization: key=' . $from,
                    'Content-Type: application/json'
                );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        // dd($result);
        curl_close( $ch );

        $notification = new Notification();
        $notification->type = $type;
        $notification->auth_id = $this->auth_id();
        $notification->action_id = $action_id;
        $notification->object_id = $object_id;
        $notification->save();


    }

    public function auth_id()
    {
        return Auth::user()->id;
    }

    public function apiResponse($resultType, $data, $code, $total_page = null, $message = null)
    {


        $response = [];

        if ($resultType == ResultType::Error || $resultType == ResultType::NotFound)
        $response['success'] = false;
        else
        $response['success'] = true;

        if ($resultType == ResultType::Custom)
        $response['message'] = $message;
        else
        $response['message'] = $resultType;

        if ($resultType != ResultType::Error && $resultType != ResultType::NotFound)
        $response['data'] = $data;

        if ($resultType == ResultType::Error)
        $response['errors'] = $data;

        if ($total_page != null)
        $response['total_page'] = $total_page;

        return response($response, $code);
    }

     /**
     * Check Authorized User whether follows or doesn't a post publisher.
     *
     * @return \Illuminate\Http\Response
     */

    public function check_post($id)
    {
        $post = Post::findOrFail($id);
        $user_id = $post->user_id;
        $user = User::find($user_id);
        $following = Following::where('follower_id', $this->auth_id())->where('followed_id', $user_id)->where('accept', true)->count();
        if ($following == 0 && $post->user_id != $this->auth_id() && $user->hidden_profile != false)
        return false;
        else
        return true;
    }

    public function is_blocked()
    {
        $blocking = Blocking::where('blocker_id', $this->auth_id())->orWhere('blocked_id', $this->auth_id())->get();

        $block_array = array();
        foreach ($blocking as $b)
        {
            if ($b->blocker_id != $this->auth_id())
            $block_array[] = $b->blocker_id;
            else
            $block_array[] = $b->blocked_id;
        }

        return $block_array;

    }

    public function is_following()
    {
        $following = Following::where('follower_id', $this->auth_id())->where('accept', true)->get();

        $follow_array = array();
        foreach ($following as $f)
        {
            $follow_array[] = $f->followed_id;
        }

        return $follow_array;

    }

    public function is_viewed($id)
    {
        $view = StoryView::find($id);
        if ($view->where('user_id', $this->auth_id())->exists())
        return true;
        else
        return false;
    }

    public function has_showned()
    {
        $story = StoryView::where('user_id', $this->auth_id())->select('user_id')->get();
        $data = array();
        foreach ($story as $s) {
            $data[] = $s->story_id;
        }
    }



 }


 class ResultType
{
    const Create = 'Data has been created';
    const Error = 'Error occurred';
    const Update = 'Data has been updated';
    const Delete = 'Data has been deleted';
    const NotFound = 'Data not found';
    const Fetch = 'Data has been fetched';
    const Custom = '';
}

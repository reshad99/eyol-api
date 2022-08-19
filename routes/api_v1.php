<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Story Show duzelt
Route::middleware(['auth:api', 'throttle:60,1'])->group(function(){
    Route::get('story/group', 'App\Http\Controllers\Api\V1\StoryController@group_by');
    Route::apiResources([
        'posts' => 'App\Http\Controllers\Api\V1\PostController',
        'comments' => 'App\Http\Controllers\Api\V1\CommentController',
        'likes' => 'App\Http\Controllers\Api\V1\LikeController',
        'story' => 'App\Http\Controllers\Api\V1\StoryController',
    ]);

    Route::get('story/user/{id}', 'App\Http\Controllers\Api\V1\StoryController@user_stories');
    Route::post('comment/write/{id}', 'App\Http\Controllers\Api\V1\CommentController@write_comment');
    Route::get('like/post/{id}', 'App\Http\Controllers\Api\V1\LikeController@like_unlike_post');
    Route::get('like/comment/{id}', 'App\Http\Controllers\Api\V1\LikeController@like_unlike_comment');
    Route::get('comment/likes/{id}', 'App\Http\Controllers\Api\V1\LikeController@index_comment_likes');
    Route::get('block/{id}', 'App\Http\Controllers\Api\V1\BlockController@block_unblock');
    Route::get('follow/{id}', 'App\Http\Controllers\Api\V1\FollowController@follow');
    Route::get('unfollow/{id}', 'App\Http\Controllers\Api\V1\FollowController@unfollow');
    Route::get('profile/{id}', 'App\Http\Controllers\Api\V1\ProfileController@show');
    Route::get('profile/{id}/videos', 'App\Http\Controllers\Api\V1\ProfileController@videos');
    Route::get('followers', 'App\Http\Controllers\Api\V1\FollowController@followers');
    Route::get('followings', 'App\Http\Controllers\Api\V1\FollowController@followings');
    Route::get('replies/{comment_id}', 'App\Http\Controllers\Api\V1\ReplyController@index');
    Route::post('replies/{comment_id}', 'App\Http\Controllers\Api\V1\ReplyController@store');
    Route::delete('replies/{comment_id}', 'App\Http\Controllers\Api\V1\ReplyController@destroy');
    Route::post('profile/update', 'App\Http\Controllers\Api\V1\ProfileController@update');
    Route::get('follow/accept/{id}', 'App\Http\Controllers\Api\V1\FollowController@accept_request');
    Route::get('explore', 'App\Http\Controllers\Api\V1\ExploreController@explore');

});

Route::post('login', 'App\Http\Controllers\Api\V1\AuthController@login')->name('login');
Route::post('register', 'App\Http\Controllers\Api\V1\AuthController@register')->name('register');
Route::apiResource('users', 'App\Http\Controllers\Api\V1\UserController');



<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\v1\comment\CommentLikeResource;
use App\Http\Resources\V1\Like\LikeResource;
use App\Http\Resources\V1\Post\PostResource;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
use App\Models\LikeComment;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
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
     * Like a post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like_unlike_post($id)
    {
        if($this->check_post($id) == false)
        return response(['success' => false, 'message' => 'Restricted operation. You\'re not following the publisher of this post']);

        $post = Post::find($id);
        $user_id = $post->user_id;
        if (in_array($user_id, $this->is_blocked()))
        return response(['success' => false, 'message' => 'You can not like this post']);

        $like = Like::where('user_id', $this->auth_id())->where('post_id', $id)->first();
        if($like)
        {
            $post->like_amount -= 1;
            $post->update();
            $like->delete();

            return response(['success' => true, 'message' => 'Post Unliked'], 200);
        }
        else
        {

            $post->like_amount += 1;
            $post->update();

            Like::create(['user_id' => $this->auth_id(), 'post_id' => $id]);
            $this->notification_save('likePost', $user_id, $id);
            return response(['success' => true, 'message' => 'Post Liked'], 200);
        }

    }

    /**
     * Like a comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like_unlike_comment($id)
    {
        $comment = Comment::find($id);
        $user_id = $comment->user_id;

        if (in_array($user_id, $this->is_blocked()))
        return response(['success' => false, 'message' => 'You can not follow this user. This user is blocked']);

        $like = LikeComment::where('user_id', $this->auth_id())->where('comment_id', $id)->first();
        if($like)
        {
            $comment->like_amount -= 1;
            $comment->update();
            $like->delete();

            return response(['success' => true, 'message' => 'Comment Unliked'], 200);
        }
        else
        {


            $comment->like_amount += 1;
            $comment->update();
            LikeComment::create(['user_id' => $this->auth_id(), 'comment_id' => $id]);
            $this->notification_save('likeComment', $user_id, $id);
            return response(['success' => true, 'message' => 'Comment Liked'], 200);
        }

    }

    public function index_comment_likes(Request $request, $id)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $likes = LikeComment::query();

        $likes->where('comment_id', $id)->orderByDesc('id');

        if($request->has('sortBy'))
        $likes->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $likes->orderBy('id', 'DESC');

        $data = $likes->paginate($limit);

        return $this->apiResponse(ResultType::Fetch, CommentLikeResource::collection($data), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->check_post($id) == false)
        return response(['success' => false, 'message' => 'Restricted operation. You\'re not following the publisher of this post']);

        $likes = Like::where('post_id', $id)->whereNotIn('user_id', $this->is_blocked())->orderBy('id', 'DESC')->get();
        return $this->apiResponse(ResultType::Fetch, LikeResource::collection($likes), 200);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\V1\Comment\CommentResource;
use App\Models\Blocking;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{


    /**
     * Write a comment to the post.
     *
     * @return \Illuminate\Http\Response
     */

    public function write_comment(Request $request, $id)
    {
        try
        {
            if($this->check_post($id) == false)
            return response(['success' => false, 'message' => 'Restricted operation. You\'re not following the publisher of this post'], 400);

            if($post = Post::findOrFail($id) )
            {
                $comment = new Comment;
                $comment->user_id = $this->auth_id();
                $comment->post_id = $id;
                $comment->comment = $request->comment;
                $comment->save();

                $this->notification_save('comment', $post->user->id, $comment->id);
            }
            return $this->apiResponse(ResultType::Create, new CommentResource($comment), 201);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //Bloklama yerlesdir
        if($this->check_post($id) == false)
        return response(['success' => false, 'message' => 'Restricted operation. You\'re not following the publisher of this post']);

        $limit = $request->has('limit') ? $request->limit : 20;
        $comments = Comment::query();

        $comments->whereNotIn('user_id', $this->is_blocked());

        if($request->has('sortBy'))
        $comments->orderBy($request->sortBy, $request->query('sort', 'DESC'));

        $comments->where('post_id', $id)->orderBy('id', 'DESC')->get();
        $data = $comments->paginate($limit);

        return $this->apiResponse(ResultType::Fetch, CommentResource::collection($data), 200, $data->lastPage());
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
        try
        {
            $comment = Comment::findOrFail($id);
            if ($comment->user_id == $this->auth_id())
            $comment->delete();
            else
            return response(['success' => false, 'message' => 'Restricted Operation']);

            return response(['success' => true, 'message' => 'Comment Deleted']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

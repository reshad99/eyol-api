<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\ResultType;
use App\Http\Requests\v1\Reply\ReplyStoreRequest;
use App\Http\Resources\v1\Reply\ReplyResource;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function index(Request $request, $comment_id)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $reply = Reply::query();

        $reply->where('comment_id', $comment_id);

        if($request->has('sortBy'))
        $reply->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $reply->orderBy('id', 'DESC');

        $data = $reply->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, ReplyResource::collection($data), 200, $data->lastPage());
    }

    public function store(ReplyStoreRequest $request, $comment_id)
    {
        $text = $request->text;

        $reply = new Reply();
        $reply->text = $text;
        $reply->user_id = $this->auth_id();
        $reply->comment_id = $comment_id;
        $reply->save();

        $comment = Comment::find($comment_id);
        $this->notification_save('likeComment', $comment->user_id, $comment_id);

        return $this->apiResponse(ResultType::Create, new ReplyResource($reply), 201);
    }

    public function destroy($id)
    {
        $reply = Reply::find($id);
        $reply->delete();

        return $this->apiResponse(ResultType::Delete, null, 200);
    }
}

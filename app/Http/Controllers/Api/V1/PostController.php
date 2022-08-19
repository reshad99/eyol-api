<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\V1\Post\PostStoreRequest;
use App\Http\Resources\V1\Comment\CommentResource;
use App\Http\Resources\V1\Post\PostResource;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\User\UserShowResource;
use App\Models\Following;
use App\Models\Gallery;
use App\Models\Mention;
use App\Models\Post;
use ArrayIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use MultipleIterator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 20;
        $post = Post::query();

        $post->whereIn('user_id', $this->is_following());
        $post->whereNotIn('user_id', $this->is_blocked());
        $post->orWhere('user_id', $this->auth_id());

        if($request->has('sortBy'))
        $post->orderBy($request->sortBy, $request->query('sort', 'DESC'));
        else
        $post->orderBy('id', 'DESC');

        $data = $post->paginate($limit);
        return $this->apiResponse(ResultType::Fetch, PostResource::collection($data), 200, $data->lastPage());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        try
        {
            Post::where('id', '>', 20)->delete();
            Storage::deleteDirectory('images');
            $video = ['mp4', 'mov', 'avi'];
            $image = ['jpg', 'png', 'gif', 'jpeg', 'webp'];
            $media_type = '';
            $caption = $request->caption;
            $media   = $request->media;
            $thumbnail = $request->thumbnail;

            $post = new Post;
            $post->caption = $caption;
            $post->user_id = $this->auth_id();
            $post->save();
            $post_id = $post->id;

            if ($request->has('mention'))
            {
                foreach($request->mention as $m)
                {
                    $mention = new Mention;
                    $mention->user_id = $m;
                    $mention->post_id = $post_id;
                    $mention->save();
                }
            }

            // $mi = new MultipleIterator();
            // $mi->attachIterator(new ArrayIterator($media));
            // $mi->attachIterator(new ArrayIterator($thumbnail));

            $thumb_key = 0;
            foreach ($media as $m) {
                if($m->getSize() > 52428800)
                return response(['success' => false,'message' => 'Yüklədiyiniz fayl 50 mb\'dan artıq olmamalıdır']);

                $extension = $m->getClientOriginalExtension();
                $photo_name = uniqid().'.'.$m->getClientOriginalExtension();
                $path = 'images/posts/'.$photo_name;
                $m->storeAs('images/posts/', $photo_name, 'public');

                if (in_array($extension, $video))
                {
                    $thumb_name = uniqid().'.'.$thumbnail[$thumb_key]->getClientOriginalExtension();
                    $thumbnail[$thumb_key]->storeAs('images/thumbs/', $thumb_name, 'public');
                    $thumb_key += 1;
                    $media_type = 'video';
                }
                elseif(in_array($extension, $image))
                {
                    $thumb_name = null;
                    $media_type = 'image';
                }
                else
                return response(['success' => false,'message' => 'Yüklədiyiniz fayl növü uyğun deyil']);


                $gallery = new Gallery;
                $gallery->media_type = $media_type;
                $gallery->thumbnail = $thumb_name;
                $gallery->url = $path;
                $gallery->post_id = $post_id;
                $gallery->save();

            }

        return $this->apiResponse(ResultType::Create, new PostResource($post), 201);

        }
        catch (\Throwable $th)
        {
            throw $th;
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            if($this->check_post($id) == false)
            return response(['success' => false, 'message' => 'Restricted operation. You\'re not following the publisher of this post']);

            $post = Post::findOrFail($id);

            return $this->apiResponse(ResultType::Fetch, new PostResource($post), 200);
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
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
     * Archive specified post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function archive($id, Request $request)
    {
        try
        {
            $post = Post::findOrFail($id);
            $status = $request->status;
            $post->archived = $status;
            $post->update();
            return $this->apiResponse(ResultType::Update, new UserShowResource($post), 200);
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
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
            $post = Post::findOrFail($id);
            $gallery = $post->gallery;

            foreach ($gallery as $g)
            {
                Storage::delete($g->url);
                Storage::delete($g->thumbnail);
            }
            $post->delete();
            return $this->apiResponse(ResultType::Delete, new UserShowResource($post), 200);
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
    }
}

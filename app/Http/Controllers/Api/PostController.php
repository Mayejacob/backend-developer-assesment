<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\PostResource as PostResource;
use Illuminate\Support\Facades\Validator;


class PostController extends BaseController
{
    
    public function index()
    {
        $posts = Post::all();
        return $this->sendResponse(PostResource::collection($posts), 'Posts fetched successfully.');

    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $blog = Post::create($input);
        return $this->sendResponse(new PostResource($blog), 'Post created successfully.');
    }
    public function show($id)
    {
        $blog = Post::find($id);
        if (is_null($blog)) {
            return $this->sendError('Blog Post does not exist.');
        }
        return $this->sendResponse(new PostResource($blog), 'Post fetched successfully.');
    }
    public function update(Request $request, Post $post)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->save();
        
        return $this->sendResponse(new PostResource($post), 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return $this->sendResponse([], 'Post deleted successfully.');
    }
}

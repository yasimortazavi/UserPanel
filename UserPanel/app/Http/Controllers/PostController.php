<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends ApiController
{
    public function index()
    {
        return $this->successResponse(Post::all(), 200);
    }

    public function show(Post $post)
    {
        return $this->successResponse($post, 200);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'required|image',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();

        $request->image->storeAs('images/posts' , $imageName, 'public');

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imageName,
            // 'user_id' => $request->user_id,
        ]);

        return $this->successResponse($post, 201);
    }
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if ($request->has('image')) {
            $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/posts', $imageName, 'public');
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->has('image') ? $imageName : $post->image,
            'user_id' => $request->user_id,
        ]);

        return $this->successResponse($post, 200);
    }
    public function delete(Post $post)
    {
        $post = $post->delete();
        return $this->successResponse($post, 200);
    }
}

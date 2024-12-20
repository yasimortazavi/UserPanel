<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostConteoller extends Controller
{
    public function index()
    {
        return $this->successResponse(Post::all(), 200);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'body' => 'required|string',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image,
            'user_id' => $request->user_id,
        ]);

        return $this->successResponse($post, 201);
    }
}

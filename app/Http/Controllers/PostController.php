<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // ADD POST
    public function addPost(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|unique:posts',
            'content' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);

            return response()->json(data: [
                'message' => 'Post created successfully',
                'post' => $post,
                'post_id' => $post->id,
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage(), $err], status: 403);
        }
    }
}

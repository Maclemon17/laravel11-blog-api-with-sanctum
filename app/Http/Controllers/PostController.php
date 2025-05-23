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

    // EDIT POST
    public function editPost(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'post_id' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $post = Post::find($request->post_id);

            if ($post->user_id !== Auth::id()) {
                return response()->json(data: ['error' => 'Unauthorized'], status: 403);
            }

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json(data: [
                'message' => 'Post updated successfully',
                'post' => $post,
                'post_id' => $post->id,
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage(), $err], status: 403);
        }
    }

    // EDIT POST
    public function editPost2(Request $request, $post_id): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            // 'post_id' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $post = Post::find($post_id);

            if ($post->user_id !== Auth::id()) {
                return response()->json(data: ['error' => 'Unauthorized'], status: 403);
            }

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json(data: [
                'message' => 'Post updated successfully',
                'post' => $post,
                'post_id' => $post->id,
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage(), $err], status: 403);
        }
    }


    // GET ALL POST
    public function getAllPosts(): JsonResponse 
    {
        try {
            $posts = Post::all();

            return response()->json(data: [
                'posts' => $posts,
                'status' => true,
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: [
                'error' => $err->getMessage(),
            ], status: 403);
        }
    }


    // GET SINGLE POST
    public function getPost($post_id): JsonResponse
    {
        try {
            // $post = Post::find($post_id);
            $post = Post::with('author', 'comment', 'likes')->where('id', $post_id)->first();

            return response()->json(data: [
                'post' => $post,
                'status' => false,
            ], status: 200);
        } catch (\Throwable $err) {
            return response()->json(data: [
                'error' => $err->getMessage(),
            ], status: 403);
        }
    }

    // DELETE POST
    public function deletePost($post_id): JsonResponse
    {
        try {
            $post = Post::find($post_id);

            if ($post->user_id !== Auth::id()) {
                return response()->json(data: [
                    'error' => 'Unauthorized'
                ], status: 403);
            }

            $post->delete();

            return response()->json(data: [
                'message' => 'Post deleted successfully',
                'status' => true,
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: [
                'error' => $err->getMessage(),
            ], status: 403);
        }
    }

    
}

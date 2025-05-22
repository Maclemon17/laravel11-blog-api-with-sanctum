<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    // ADD LIKE
    public function addLike(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'post_id' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            // check if user already liked the post
            $liked = Like::where('post_id', $request->post_id)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($liked) {
               return response()->json(data: ['message' => "Post already liked"], status: 403);
            }

            $like = new Like();
            $like->post_id = $request->post_id;
            $like->user_id = Auth::id();

            $like->save();


            return response()->json(data: [
                'message' => 'Like added successfully',
                'status' => true,
            ], status: 200);
        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage(), $err], status: 403);
        }
    }
}

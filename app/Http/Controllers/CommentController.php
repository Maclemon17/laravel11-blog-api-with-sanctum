<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    // ADD COMMENT
    public function addComment(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'post_id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            Comment::create([
                'post_id' => $request->post_id,
                'comment' => $request->comment,
            ]);

            return response()->json(data: [
                'message' => 'Comment added successfully',
                'status' => true,
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage(), $err], status: 403);
        }
    }
}

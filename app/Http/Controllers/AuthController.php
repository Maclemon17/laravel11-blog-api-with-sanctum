<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\Facades\Validator;

class AuthController extends Controller
{
    //REGISTER USER
    public function register(Request $request): JsonResponse{

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json(data: [
                'access_token' => $token,
                'user' => $user
            ], status: 200);

        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage()], status: 403);
        }
    }

    
    // lOGIN USER
    public function login(Request $request) : JsonResponse {
        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        try {
            if (!Auth::attempt($credentials)) {
                return response()->json(data: ['error' => 'Invalid credentials'], status: 403);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(data: [
                'access_token' => $token,
                'user' => $user
            ], status: 200);
        } catch (\Throwable $err) {
            return response()->json(data: ['error' => $err->getMessage()], status: 403);
        }

    }


    // LOGOUT
    function logout(Request $request) : JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json(data: [
            'message' => 'user logged out succesfully'
        ], status: 200);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['apiLogin', 'apiRegister']]);
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if(! $token = JWTAuth::attempt($credentials) ) {
            return response()->json(['error' => 'invalid credentials'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function apiUser(){
//        $user = JWTAuth::parseToken()->authenticate();
        $user = auth('api')->user();
        if($user){
            return response()->json([
                'success' => true,
                'user' => $user->only(['id', 'name', 'email', 'role']),
                'package_tier' => $user->package_tier,
                'features' => $user->tierFeatures(),
                'has_access' => $user->hasAccess('forum_access'),
                'is_active' => !$user->package_expires_at || now()->lt($user->package_expires_at)
            ]);
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

    }

    protected function respondWithToken($token,$user = null)
    {
        $currentUser = $user ?? JWTAuth::user();
        if (!$currentUser) {
            return response()->json([
                'error' => 'User not found after authentication'
            ], 401);
        }
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*60,
            'user' => [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'email' => $currentUser->email,
                'role' => $currentUser->role,
                'package_tier' => $currentUser->package_tier,
                'package_expires_at' => $currentUser->package_expires_at?->toIso8601String(),
                'features' => $currentUser->tierFeatures(),
                'is_active' => !$currentUser->package_expires_at || now()->lt($currentUser->package_expires_at),
                'created_at' => $currentUser->created_at,
            ],
        ]);
    }

    public function apiRegister(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => 'user',
            'package_tier' => 'none',
        ]);
        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token,$user);
    }

    public function apiChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password Changed successfully',
        ]);
    }
}

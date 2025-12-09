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
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function apiUser(){
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            return $user;
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*60,
            'user' => JWTAuth::user(),
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
        ]);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user,
        ]);
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

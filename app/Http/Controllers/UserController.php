<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 400);
        }

        $user           = new User();
        $user->_id     = "user-" . Str::uuid();
        $user->name     = $request->name;
        $user->email     = $request->email;
        $user->password     = bcrypt($request->password);
        $user->role     = "user";

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => [
                'user' => $user
            ]
        ], 200);
    }
 
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 400);
        }

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
        return response()->json([
            'success' => true,
            'message' => 'Login Success',
            'data' => [
                'token' => $token,
            ]
        ], 200);
    }
 
    public function logout(Request $request)
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 400);
        }
   
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], 500);
        }
    }
 
    public function get_user(Request $request)
    {
        
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request',
                'error' => $validator->messages()
            ], 400);
        }

        $user = JWTAuth::authenticate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'User retrieved succesfully',
            'data' => [
                'user' => $user
            ]
        ]);
        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\User;

class AuthController extends Controller
{
	public function register(Request $request)
    {
    	$credentials = $request->only('name', 'email', 'password', 'role');
        $rules = [
        	'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => Rule::in(['admin', 'user']),
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => $validator->messages()
            ], 401);
        }
        $user = User::create($credentials);

        $token = auth()->login($user);

        // All good so return the token
        return response()->json([
            'status' => 'success', 
            'data'=> [
                'token' => $token
                // You can add more details here as per you requirment. 
            ]
        ]);
    }

    /**
     * API Login, on success return JWT Auth token
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => $validator->messages()
            ], 401);
        }
        try {
            // Attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Incorrect email or password'
                ], 401);
            }
        } catch (JWTException $e) {
            // Something went wrong with JWT Auth.
            return response()->json([
                'status' => 'error', 
                'message' => 'Failed to login, please try again.'
            ], 500);
        }

        $user = User::where('email', $request->email)->first();
        // All good so return the token
        return response()->json([
            'status' => 'success', 
            'data'=> [
                'token' => $token,
                'email' => $user->email,
                'role' => $user->role 
                // You can add more details here as per you requirment. 
            ]
        ]);
    }

    public function password(Request $request) {
        $credentials = $request->only('new_password', 'confirm_password');
        $rules = [
            'new_password' => 'required|min:6',
            'confirm_password' => 'same:new_password',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => $validator->messages()
            ], 401);
        }
        $header = $request->header('Authorization');
        $parts = explode (" ", $header);  
        $token = $parts[1];
        $user = JWTAuth::toUser($token);
        $user->password = $request->new_password;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'password changed successfully',
            'data'=> [
                'user' => $user
            ]
        ]);
    }
}

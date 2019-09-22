<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
	public function __construct()
	{
	    $this->middleware('admin');
	}

	public function create(Request $request)
    {
	    $credentials = $request->only('name', 'email', 'password', 'role');
	    $rules = [
	    	'name' => 'required|string|max:255',
	        'email' => 'required|email',
	        'password' => 'required|min:6',
	        'role' => 'required|in:admin,user',
	    ];
	    $validator = Validator::make($credentials, $rules);
	    if($validator->fails()) {
	        return response()->json([
	            'status' => 'error', 
	            'message' => $validator->messages()
	        ], 401);
	    }
	    $user = User::create($credentials);

	    return response()->json([
	        'status' => 'success', 
	        'data'=> [
	            'user' => $user 
	        ]
	    ]);
	}

	public function list()
    {
    	$users = User::all();
    	return response()->json([
	        'status' => 'success', 
	        'data'=> [
	            'users' => $users 
	        ]
	    ]);
    }

    public function read($id)
    {
    	$user = User::find($id);
    	return response()->json([
	        'status' => 'success', 
	        'data'=> [
	            'user' => $user 
	        ]
	    ]);
    }

    public function update(Request $request, $id)
    {
    	$credentials = $request->only('name', 'email', 'password', 'role');
	    $rules = [
	    	'name' => 'required|string|max:255',
	        'email' => 'required|email',
	        'password' => 'min:6',
	        'role' => 'required|in:admin,user',
	    ];
	    $validator = Validator::make($credentials, $rules);
	    if($validator->fails()) {
	        return response()->json([
	            'status' => 'error', 
	            'message' => $validator->messages()
	        ], 401);
	    }
	    $user = User::find($id);
	    $updated = $user->update($credentials);
	    if($updated) {
    		return response()->json([
		        'status' => 'success',
		        'message' => 'user updated successfully',
		        'data'=> [
		            'user' => User::find($id) 
		        ]
		    ]);
    	}
    	return response()->json([
	        'status' => 'fail',
	        'message' => 'something went wrong',
	    ]);
    }

    public function delete($id)
    {
    	$user = User::find($id);
    	$deleted = $user->delete();
    	if($deleted) {
    		return response()->json([
		        'status' => 'success',
		        'message' => 'user deleted successfully',
		        'data'=> [
		            'user' => $user 
		        ]
		    ]);
    	}
    	return response()->json([
	        'status' => 'fail',
	        'message' => 'something went wrong',
	    ], 401);
    }
}

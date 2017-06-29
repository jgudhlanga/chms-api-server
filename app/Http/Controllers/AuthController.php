<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function auth(Request $request)
	{

		$params = $request->only('email', 'password');

		$username = $params['email'];
		$password = $params['password'];

		if(Auth::attempt(['email' => $username, 'password' => $password])){
			return Auth::user()->createToken('my_user', []);
		}

		return response()->json(['error' => 'Invalid username or Password']);
	}

	public function user(Request $request)
	{
		return $request->user();
	}
}

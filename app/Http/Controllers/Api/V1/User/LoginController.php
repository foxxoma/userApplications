<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	public function __invoke(Request $request)
	{
		$credentials = $request->only('email', 'password');

		if (!Auth::attempt($credentials)) {
			return response()->json([
				'message' => 'You cannot sign with those credentials',
				'errors' => 'Unauthorised'
			], 401);
		}

		$token = Auth::user()->createToken(config('app.name'));

		return response()->json([
			'token_type' => 'Bearer',
			'token' => $token->plainTextToken,
		], 200);
	}
}

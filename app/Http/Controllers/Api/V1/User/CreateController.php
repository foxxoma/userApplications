<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateUserFormRequest;
use App\Models\User;

class CreateController extends Controller
{
	public function __invoke(CreateUserFormRequest $request)
	{
		$user = User::create(array_merge(
			$request->validated(),
			['password' => bcrypt($request->password)],
		));

		$token = $user->createToken(config('app.name'));

		return response()->json([
			'token_type' => 'Bearer',
			'token' => $token->plainTextToken,
		], 200);
	}
}
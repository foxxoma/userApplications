<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\UserResource;

class ShowController extends Controller
{
	public function __invoke(Request $request)
	{
		return new UserResource($request->user());
	}
}

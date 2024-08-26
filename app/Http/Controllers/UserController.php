<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function user(Request $request): JsonResponse
	{
		return response()->json(new UserResource($request->user()));
	}
}

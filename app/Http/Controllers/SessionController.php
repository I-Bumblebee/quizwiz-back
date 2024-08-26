<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
	public function register(RegisterUserRequest $request): JsonResponse
	{
		$user = User::create($request->validated());

		$user->sendEmailVerificationNotification();

		return response()->json([], 201);
	}

	public function login(LoginUserRequest $request): JsonResponse
	{
		$credentials = $request->only(['email', 'password']);
		$remember = $request->boolean('remember');

		if (!Auth::attempt($credentials, $remember)) {
			return response()->json([
				'errors'  => [
					'email'    => ['The provided Email or Password is incorrect.'],
					'password' => ['The provided Email or Password is incorrect.'],
				],
			], 401);
		}

		return response()->json(new UserResource(Auth::user()));
	}

	public function logout(): JsonResponse
	{
		Auth::logout();
		return response()->json();
	}
}

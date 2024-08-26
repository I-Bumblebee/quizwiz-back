<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
	public function verify(User $user, string $hash): JsonResponse
	{
		if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
			return response()->json([], 400);
		}

		$user->markEmailAsVerified();

		return response()->json();
	}

	public function sendVerificationEmail(SendEmailVerificationRequest $request): JsonResponse
	{
		$user = Auth::getProvider()->retrieveByCredentials($request->only('email'));

		if ($user->hasVerifiedEmail()) {
			return response()->json([
				'errors' => [
					'email' => 'The email is already verified.',
				],
			], 400);
		}

		$user->sendEmailVerificationNotification();

		return response()->json();
	}
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\URL;

class EmailVerificationUrlService
{
	public function createVerificationUrl(User $notifiable): string
	{
		$baseUrl = config('app.frontend_url');
		$user = $notifiable->getKey();
		$hash = sha1($notifiable->getEmailForVerification());

		$temporarySignedUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(config('auth.verification.expire', 60)),
			[
				'user' => $user,
				'hash' => $hash,
			],
			false
		);

		$urlComponents = parse_url($temporarySignedUrl);
		parse_str($urlComponents['query'], $params);

		return $baseUrl . "/verify-email?user=$user&hash=$hash&expires={$params['expires']}&signature={$params['signature']}";
	}
}

<?php

namespace App\Services;

use App\Models\User;

class ResetPasswordUrlService
{
	public function createPasswordResetUrl(User $notifiable, string $token): string
    {
        return config('app.frontend_url') . '/reset-password?token=' . $token . '&email=' . $notifiable->getEmailForPasswordReset();
	}
}

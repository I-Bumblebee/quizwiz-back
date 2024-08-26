<?php

namespace App\Notifications;

use App\Services\EmailVerificationUrlService;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmailBase
{
	protected EmailVerificationUrlService $verificationUrlService;

	public function __construct()
	{
		$this->verificationUrlService = new EmailVerificationUrlService();
	}

	public function verificationUrl($notifiable): string
	{
		return $this->verificationUrlService->createVerificationUrl($notifiable);
	}

	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage)
			->subject('Verify Email Address')
			->view('notifications.email', [
				'url'           => $this->verificationUrl($notifiable),
				'heading'       => 'Verify your email <br> address to get started',
				'salutation'    => 'Hi ' . $notifiable->name . ',',
				'text'          => "You're almost there! To complete your sign up, please verify your email address.",
				'buttonText'    => 'Verify now',
			]);
	}
}

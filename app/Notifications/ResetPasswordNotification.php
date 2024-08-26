<?php

namespace App\Notifications;

use App\Services\ResetPasswordUrlService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
	use Queueable;

	public ResetPasswordUrlService $resetPasswordUrlService;

	public string $token;

	public function __construct(string $token)
	{
		$this->resetPasswordUrlService = new ResetPasswordUrlService();
		$this->token = $token;
	}

	public function via($notifiable): array
	{
		return ['mail'];
	}

	protected function generateUrl($notifiable): string
	{
		return $this->resetPasswordUrlService->createPasswordResetUrl($notifiable, $this->token);
	}

	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage())
			->subject('Reset Password Notification')
			->view('notifications.email', [
				'url'           => $this->generateUrl($notifiable),
				'heading'       => 'Verify your email <br> to reset your password.',
				'salutation'    => 'Hi ' . $notifiable->name . ',',
				'text'          => "You're almost there! Just click the button below to reset your password.",
				'buttonText'    => 'Reset Password',
			]);
	}
}

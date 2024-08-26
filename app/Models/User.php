<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasFactory, Notifiable, HasApiTokens, MustVerifyEmail;

	protected $fillable = [
		'name',
		'email',
		'image',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
		'email_verified_at',
		'created_at',
		'updated_at',
	];

	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password'          => 'hashed',
		];
	}

	public function sendPasswordResetNotification($token): void
	{
		$this->notify(new ResetPasswordNotification($token));
	}

	public function sendEmailVerificationNotification(): void
	{
		$this->notify(new VerifyEmailNotification());
	}

	public function quizzes(): BelongsToMany
	{
		return $this->belongsToMany(Quiz::class);
	}
}

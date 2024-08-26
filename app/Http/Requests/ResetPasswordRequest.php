<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'token'                 => 'required',
			'email'                 => 'required|email|exists:users,email',
			'password'              => 'required|min:3|confirmed',
			'password_confirmation' => 'required|min:3',
		];
	}
}

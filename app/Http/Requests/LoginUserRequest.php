<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'email'    => 'required|email|exists:users,email',
			'password' => 'required|string',
			'remember' => 'boolean',
		];
	}
}

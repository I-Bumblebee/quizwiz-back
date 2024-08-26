<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'                  => 'required|min:3|unique:users',
			'email'                 => 'required|email|unique:users',
			'password'              => 'required|min:3|confirmed',
			'password_confirmation' => 'required',
		];
	}
}

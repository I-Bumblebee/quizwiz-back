<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteQuizRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'quiz_user_id' => 'required|exists:quiz_user,id',
		];
	}
}

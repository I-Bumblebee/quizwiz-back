<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexQuizRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'search'            => 'string|nullable',
			'sort'              => 'string|nullable',
			'order'             => 'string|required_with:sort|in:asc,desc',
			'difficulty-levels' => 'array|nullable',
			'categories'        => 'array|nullable',
			'my-quizzes'        => 'boolean|nullable',
			'not-completed'     => 'boolean|nullable',
		];
	}
}

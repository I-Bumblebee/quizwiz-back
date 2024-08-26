<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuestionOption extends Resource
{
	public static string $model = \App\Models\QuestionOption::class;

	public static $title = 'id';

	public static $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Option')
				->rules('required', 'max:255'),

			Boolean::make('Is Correct')
				->rules('required'),

			BelongsTo::make('Question', 'quizQuestion', QuizQuestion::class)
				->rules('required'),
		];
	}
}

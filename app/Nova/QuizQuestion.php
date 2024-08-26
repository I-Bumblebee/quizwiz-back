<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizQuestion extends Resource
{
	public static string $model = \App\Models\QuizQuestion::class;

	public static $title = 'id';

	public static $search = [
		'id',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Question')
				->rules('required'),

			Number::make('Points')
				->rules('required'),

			BelongsTo::make('Quiz', 'quiz', Quiz::class)
				->rules('required'),

			HasMany::make('Options', 'options', QuestionOption::class),
		];
	}
}

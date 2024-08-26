<?php

namespace App\Nova;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class DifficultyLevel extends Resource
{
	public static string $model = \App\Models\DifficultyLevel::class;

	public static $title = 'name';

	public static $search = [
		'id',
		'name',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Name')
				->sortable()
				->rules('required', 'max:255')
				->creationRules('unique:difficulty_levels,name'),

			Color::make('Color'),

			HasMany::make('Quizzes', 'quizzes', Quiz::class),
		];
	}
}

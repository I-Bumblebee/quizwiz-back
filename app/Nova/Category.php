<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
	public static string $model = \App\Models\Category::class;

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
				->creationRules('unique:categories,name'),

			BelongsToMany::make('Quizzes', 'quizzes', Quiz::class),
		];
	}
}

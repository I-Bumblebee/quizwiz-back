<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Quiz extends Resource
{
	public static string $model = \App\Models\Quiz::class;

	public static $title = 'id';

	public static $search = [
		'id',
		'title',
	];

	public static function indexQuery(NovaRequest $request, $query): Builder
	{
		return $query->withCount('users');
	}

	public function filters(NovaRequest $request): array
	{
		return [
			new \App\Nova\Filters\DifficultyLevel,
			new \App\Nova\Filters\Category,
			new \App\Nova\Filters\QuizCompletionFilter,
		];
	}

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Title')->sortable(),

			Text::make('Short Info')
				->rules('required', 'max:255')
				->hideFromIndex(),

			Image::make('Image')
				->path('images')
				->rules('image'),

			Text::make('Description')
				->rules('required')
				->hideFromIndex(),

			Number::make('Time Limit Minutes')
				->rules('required'),

			BelongsTo::make('DifficultyLevel', 'difficultyLevel', DifficultyLevel::class)
				->rules('required'),

			Number::make('Total Points'),

			Number::make('Number of Questions'),

			BelongsToMany::make('Users that took this quiz', 'users', User::class)
				->fields(function () {
					return [
						Number::make('Score')
							->rules('required'),
						Number::make('Time Taken Seconds')
							->rules('required'),
					];
				}),

			BelongsToMany::make('Categories', 'categories', Category::class),

			HasMany::make('Questions', 'questions', QuizQuestion::class),

			Number::make('Popularity', 'users_count')->sortable()->onlyOnIndex(),

			Date::make('Created At')->sortable()->onlyOnIndex(),
		];
	}
}

<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizCompletionFilter extends BooleanFilter
{
	public function apply(NovaRequest $request, $query, $value): Builder
	{
		if ($value['my-quizzes']) {
			return $query->whereHas('users', function ($query) use ($request) {
				$query->where('user_id', $request->user()->id);
			});
		}

		if ($value['not-completed']) {
			return $query->whereDoesntHave('users', function ($query) use ($request) {
				$query->where('user_id', $request->user()->id);
			});
		}

		return $query;
	}

	public function options(Request $request): array
	{
		return [
			'My Quizzes'    => 'my-quizzes',
			'Not Completed' => 'not-completed',
		];
	}
}

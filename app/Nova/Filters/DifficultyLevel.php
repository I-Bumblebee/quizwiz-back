<?php

namespace App\Nova\Filters;

use App\Models\DifficultyLevel as DifficultyLevelModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class DifficultyLevel extends BooleanFilter
{
	public function apply(NovaRequest $request, $query, $value): Builder
	{
		$selectedDifficultyLevels = array_keys(array_filter($value));

		if (empty($selectedDifficultyLevels)) {
			return $query;
		}

		return $query->whereIn('difficulty_level_id', $selectedDifficultyLevels);
	}

	public function options(Request $request): array
	{
		return DifficultyLevelModel::all()->pluck('id', 'name')->toArray();
	}
}

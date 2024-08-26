<?php

namespace App\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Category as CategoryModel;

class Category extends BooleanFilter
{
	public function apply(NovaRequest $request, $query, $value): Builder
	{
		$selectedCategories = array_keys(array_filter($value));

		if (empty($selectedCategories)) {
			return $query;
		}

		return $query->withWhereHas('categories', function ($query) use ($selectedCategories) {
			$query->whereIn('category_id', $selectedCategories);
		});
	}

	public function options(NovaRequest $request): array
	{
		return CategoryModel::all()->pluck('id', 'name')->toArray();
	}
}

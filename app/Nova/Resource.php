<?php

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Illuminate\Database\Eloquent\Builder;
use Laravel\scout\Builder as ScoutBuilder;

abstract class Resource extends NovaResource
{
	public static function indexQuery(NovaRequest $request, $query): Builder
	{
		return $query;
	}

	public static function scoutQuery(NovaRequest $request, $query): ScoutBuilder
	{
		return $query;
	}

	public static function detailQuery(NovaRequest $request, $query): Builder
	{
		return parent::detailQuery($request, $query);
	}

	public static function relatableQuery(NovaRequest $request, $query): Builder
	{
		return parent::relatableQuery($request, $query);
	}
}

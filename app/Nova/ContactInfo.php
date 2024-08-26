<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ContactInfo extends Resource
{
	public static string $model = \App\Models\ContactInfo::class;

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make(__('ID'), 'id'),
			Text::make('Email', 'email'),
			Text::make('Tel', 'telephone_number'),
			Text::make('Facebook', 'facebook'),
			Text::make('LinkedIn', 'linkedin'),
		];
	}
}

<?php

namespace App\Providers;

use App\Models\QuizQuestion;
use App\Observers\QuizQuestionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		QuizQuestion::observe(QuizQuestionObserver::class);
	}
}

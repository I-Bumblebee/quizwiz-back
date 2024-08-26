<?php

use App\Http\Middleware\EnsureQuizNotTaken;
use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\MustVerifyEmail;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();
		$middleware->alias([
			'must-verify-email'     => MustVerifyEmail::class,
			'ensure-quiz-not-taken' => EnsureQuizNotTaken::class,
			'guest'                 => GuestMiddleware::class,
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (InvalidSignatureException $e) {
			return response()->json([
				'error' => 'Invalid or expired signature.',
			], 400);
		});
	})->create();

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware extends RedirectIfAuthenticated
{
	public function handle(Request $request, Closure $next, string ...$guards): JsonResponse
	{
		$guards = empty($guards) ? [null] : $guards;

		foreach ($guards as $guard) {
			if (Auth::guard($guard)->check()) {
				return response()->json(['message' => 'You are already authenticated.'], 403);
			}
		}

		return $next($request);
	}
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureQuizNotTaken
{
	public function handle(Request $request, Closure $next): Response
	{
		$user = Auth::user();
		$quiz = $request->route('quiz');

		if ($user && $user->quizzes()->where('quiz_id', $quiz->id)->exists()) {
			return response()->json(['error' => 'Quiz Already Taken'], 403);
		}

		return $next($request);
	}
}

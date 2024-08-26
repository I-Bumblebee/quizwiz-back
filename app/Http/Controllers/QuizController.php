<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteQuizRequest;
use App\Http\Requests\IndexQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\QuizUser;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
	public function index(IndexQuizRequest $request): JsonResponse
	{
		$quizzes = Quiz::with('difficultyLevel', 'categories', 'users')
			->withCount('users')
			->applyFilterAndSort($request->validated())
			->paginate(9);

		return QuizResource::collection($quizzes)->response();
	}

	public function show(Quiz $quiz): JsonResponse
	{
		return response()->json(new QuizResource($quiz));
	}

	public function similarQuizzes(Quiz $quiz): JsonResponse
	{
		return response()->json(
			QuizResource::collection(
				Quiz::similarQuizzes($quiz)->paginate(9)
			)
		);
	}

	public function getQuizQuestions(Quiz $quiz): JsonResponse
	{
		return response()->json($quiz->questions()->get());
	}

	public function startQuiz(Quiz $quiz): JsonResponse
	{
		$user = auth()->user();

		$quizUserId = DB::table('quiz_user')->insertGetId([
			'user_id'    => $user?->id,
			'quiz_id'    => $quiz->id,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		return response()->json([
			'quiz_user_id' => $quizUserId,
		]);
	}

	public function completeQuiz(Quiz $quiz, CompleteQuizRequest $request, QuizService $quizService): JsonResponse
	{
		$quizUser = QuizUser::find($request->validated('quiz_user_id'));

		$quizUserStats = $quizService->completeQuiz($quiz, $request->all(), $quizUser);

		return response()->json([
			...$quizUser->only('score', 'time_taken_seconds'),
			...$quizUserStats,
		]);
	}
}

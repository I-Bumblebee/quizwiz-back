<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizUser;

class QuizService
{
	public function calculateScore(Quiz $quiz, array $answers): array
	{
		$quiz->load('questions');

		$score = 0;
		$correctCount = 0;

		foreach ($answers as $questionId => $questionOptionsId) {
			$question = $quiz->questions->find($questionId);
			if (!$question) {
				continue;
			}

			$correctOptions = $question->options
				->filter(function ($option) {
					return $option->is_correct;
				})
				->sortBy('id')
				->pluck('id')
				->toArray();

			sort($questionOptionsId);

			if ($correctOptions === $questionOptionsId) {
				$score += $question->points;
				$correctCount++;
			}
		}

		return [
			'score'             => $score,
			'correct_answers'   => $correctCount,
			'incorrect_answers' => $quiz->number_of_questions - $correctCount,
		];
	}

	public function completeQuiz(Quiz $quiz, array $answers, QuizUser $quizUser): array
	{
		$quizUserStats = $this->calculateScore($quiz, $answers);

		$quizUser->score = $quizUserStats['score'];
		$quizUser->time_taken_seconds = intval(abs(now()->diffInSeconds($quizUser->created_at)));
		$quizUser->save();

		return $quizUserStats;
	}
}

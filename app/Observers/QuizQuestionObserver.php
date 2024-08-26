<?php

namespace App\Observers;

use App\Models\QuizQuestion;

class QuizQuestionObserver
{
	public function created(QuizQuestion $quizQuestion): void
	{
		$quizQuestion->quiz->total_points += $quizQuestion->points;
		$quizQuestion->quiz->number_of_questions += 1;
		$quizQuestion->quiz->save();
	}

	public function deleted(QuizQuestion $quizQuestion): void
	{
		$quizQuestion->quiz->total_points -= $quizQuestion->points;
		$quizQuestion->quiz->number_of_questions -= 1;
		$quizQuestion->quiz->save();
	}

	public function updated(QuizQuestion $quizQuestion): void
	{
		$quizQuestion->quiz->total_points -= $quizQuestion->getOriginal('points');
		$quizQuestion->quiz->total_points += $quizQuestion->points;
		$quizQuestion->quiz->save();
	}
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Models\DifficultyLevel;
use App\Models\ContactInfo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$user = User::factory()->create([
			'name'     => 'admin',
			'email'    => 'admin@gmail.com',
			'password' => 'password',
		]);

		$difficultyLevels = config('difficulty_levels.difficulty_levels');

		collect(config('categories.categories'))
			->each(fn ($category) => Category::factory()->create($category));

		$categories = Category::all();

		foreach ($difficultyLevels as $difficultyLevelData) {
			DifficultyLevel::factory()
				->has(
					Quiz::factory()
						->count(4)
						->afterCreating(function ($quiz) use ($categories) {
							$quiz->categories()->attach(
								$categories->random(rand(1, 3))->pluck('id')->toArray()
							);

							QuizQuestion::factory()
								->count(4)
								->has(
									QuestionOption::factory()
										->count(3),
									'options'
								)
								->create(['quiz_id' => $quiz->id]);
						}),
					'quizzes'
				)
				->create($difficultyLevelData);
		}

		$quizzes = Quiz::all();

		$numberOfQuizzes = (int) ($quizzes->count() * 0.2);
		$quizzesSubset = $quizzes->random($numberOfQuizzes);

		foreach ($quizzesSubset as $quiz) {
			$user->quizzes()->attach($quiz->id, [
				'score'              => rand(1, $quiz->total_points),
				'time_taken_seconds' => rand(60, $quiz->time_limit_minutes * 60),
				'created_at'         => now(),
				'updated_at'         => now(),
			]);
		}

		ContactInfo::create([
			'email'                 => 'quizwiz@gmail.com',
			'telephone_number'      => '+995 328989',
			'facebook'              => 'https://www.facebook.com/quizwiz',
			'linkedin'              => 'https://www.linkedin.com/company/quizwiz',
		]);
	}
}

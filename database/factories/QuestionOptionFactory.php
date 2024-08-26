<?php

namespace Database\Factories;

use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionOption>
 */
class QuestionOptionFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'option'           => $this->faker->sentence,
			'is_correct'       => $this->faker->boolean,
			'quiz_question_id' => function () {
				return QuizQuestion::factory()->create()->id;
			},
		];
	}
}

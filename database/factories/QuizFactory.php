<?php

namespace Database\Factories;

use App\Models\DifficultyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title'               => $this->faker->sentence,
			'image'               => null,
			'short_info'          => $this->faker->text,
			'description'         => $this->faker->paragraph,
			'time_limit_minutes'  => $this->faker->numberBetween(1, 60),
			'difficulty_level_id' => function () {
				return DifficultyLevel::factory()->create()->id;
			},
		];
	}
}

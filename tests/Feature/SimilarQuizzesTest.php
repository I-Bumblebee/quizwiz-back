<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimilarQuizzesTest extends TestCase
{
	use RefreshDatabase, WithFaker;

	/** @test */
	public function show_returns_quiz_and_similar_quizzes_based_on_categories()
	{
		$categories = Category::factory()->count(2)->create();
		$mainQuiz = Quiz::factory()->create();

		$mainQuiz->categories()->attach($categories->pluck('id')->toArray());

		$similarQuizzes = Quiz::factory()->count(5)->create();
		$dissimilarQuiz = Quiz::factory()->count(2)->create();
		$similarQuizzes->each(function ($quiz) use ($categories) {
			$quiz->categories()->attach($categories->random(1)->pluck('id')->toArray());
		});

		$response = $this->get(route('quizzes.similar', $mainQuiz->id));

		$response->assertStatus(200);
		$response->assertJsonCount(5);

		$excepted = $similarQuizzes->pluck('id')->toArray();
		sort($excepted);
		$actual = array_column($response->json(), 'id');
		sort($actual);

		$this->assertEquals($excepted, $actual);
	}

	/** @test */
	public function show_returns_quiz_and_similar_quizzes_based_on_categories_and_user()
	{
		$categories = Category::factory()->count(2)->create();
		$mainQuiz = Quiz::factory()->create();
		$user = User::factory()->create();
		$this->actingAs($user);

		$mainQuiz->categories()->attach($categories->pluck('id')->toArray());

		$similarQuizzes = Quiz::factory()->count(7)->create();
		$dissimilarQuiz = Quiz::factory()->count(2)->create();
		$similarQuizzes->each(function ($quiz) use ($categories) {
			$quiz->categories()->attach($categories->random(1)->pluck('id')->toArray());
		});

		$user->quizzes()->attach($similarQuizzes->random(3)->pluck('id')->toArray());

		$response = $this->get(route('quizzes.similar', $mainQuiz->id));

		$response->assertJsonFragment(['has_taken' => false]);

		$response->assertStatus(200);
		$response->assertJsonCount(4);

		$excepted = $similarQuizzes->filter(function ($quiz) use ($user) {
			return !$user->quizzes->contains($quiz);
		})->pluck('id')->toArray();
		sort($excepted);
		$actual = array_column($response->json(), 'id');
		sort($actual);

		$this->assertEquals($excepted, $actual);
	}
}

<?php

namespace Tests\Feature;

use App\Models\DifficultyLevel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz;
use App\Models\Category;
use App\Models\User;

class FilterSortTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();

		$quizzes = Quiz::factory()->count(5)->create();

		$categories = Category::factory()->count(3)->create();

		foreach ($quizzes as $quiz) {
			$quiz->categories()->attach($categories->random(1)->pluck('id')->toArray());
		}

		$user = User::factory()->create();

		$user->quizzes()->attach($quizzes->random(2)->pluck('id')->toArray());
	}

	/** @test */
	public function user_can_filter_quizzes_by_category()
	{
		$category = Category::first();
		$quizzes = Quiz::all();

		$response = $this->get(route('quizzes.index', ['categories' => [$category->id]]));

		$quizzesInCategory = $quizzes->filter(function ($quiz) use ($category) {
			return $quiz->categories->contains($category);
		});

		$response->assertStatus(200);
		$this->assertEquals($quizzesInCategory->pluck('id')->toArray(), array_column($response->json(), 'id'));
	}

	/**
	 * @dataProvider sortProvider
	 */
	public function test_quizzes_are_sorted_correctly($sort, $order)
	{
		$quizzes = Quiz::with('difficultyLevel', 'categories', 'users')
			->withCount('users')
			->applyFilterAndSort(['sort' => $sort, 'order' => $order])
			->get()
			->makeHidden('users');

		if ($order === 'desc') {
			$expected = $quizzes->sortByDesc($sort);
		} else {
			$expected = $quizzes->sortBy($sort);
		}

		$response = $this->get(route('quizzes.index', ['sort' => $sort, 'order' => $order]));

		$response->assertStatus(200);
		$this->assertEquals($expected->toArray(), $response->json());
	}

	public static function sortProvider(): array
	{
		return [
			'sort by id ascending'          => ['id', 'asc'],
			'sort by id descending'         => ['id', 'desc'],
			'sort by title ascending'       => ['title', 'asc'],
			'sort by title descending'      => ['title', 'desc'],
			'sort by popularity ascending'  => ['users_count', 'desc'],
		];
	}

	/** @test */
	public function completed_quizzes_are_fetched_correctly()
	{
		$user = User::first();
		$completedQuizzes = $user->quizzes;

		$response = $this->actingAs($user)->get(route('quizzes.index', ['my-quizzes' => true]));

		$response->assertStatus(200);
		$this->assertEquals($completedQuizzes->pluck('id')->toArray(), array_column($response->json(), 'id'));
	}

	/** @test */
	public function can_filter_quizzes_by_difficulty_level()
	{
		$difficultyLevel = DifficultyLevel::first();
		$quizzes = Quiz::all();

		$response = $this->get(route('quizzes.index', ['difficulty-levels' => [$difficultyLevel->id]]));

		$quizzesInDifficultyLevel = $quizzes->filter(function ($quiz) use ($difficultyLevel) {
			return $quiz->difficulty_level_id === $difficultyLevel->id;
		});

		$response->assertStatus(200);
		$this->assertEquals($quizzesInDifficultyLevel->pluck('id')->toArray(), array_column($response->json(), 'id'));
	}

	public function test_can_filter_and_sort_by_category_and_difficulty()
	{
		$category = Category::factory()->create();
		$difficultyLevel = DifficultyLevel::factory()->create();
		$completedQuiz = Quiz::factory()->create();
		$user = User::factory()->create();
		$user->quizzes()->attach($completedQuiz->id);

		$quizzes = Quiz::factory()->count(5)->create();
		foreach ($quizzes as $quiz) {
			$quiz->categories()->attach($category->id);
			$quiz->difficulty_level_id = $difficultyLevel->id;
			$quiz->save();
		}

		$response = $this->get(route('quizzes.index', [
			'categories'        => [$category->id],
			'difficulty-levels' => [$difficultyLevel->id],
			'sort'              => 'title',
			'order'             => 'asc',
		]));

		$response->assertStatus(200);
		$expected = Quiz::whereHas('categories', function ($query) use ($category) {
			$query->where('category_id', $category->id);
		})->where('difficulty_level_id', $difficultyLevel->id)
			->orderBy('title', 'asc')
			->get();

		$this->assertEquals($expected->pluck('id')->toArray(), array_column($response->json(), 'id'));
	}
}

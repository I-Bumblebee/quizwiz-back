<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Quiz extends Model
{
	use HasFactory;

	protected $fillable = ['title', 'short_info', 'image', 'description', 'time_limit', 'difficulty_level_id'];

	protected $appends = ['user'];

	protected $hidden = ['created_at', 'updated_at'];

	public function getUserAttribute(): array
	{
		$userQuiz = $this->users()->where('user_id', Auth::id())->first();
		return [
			'has_taken'    => $userQuiz !== null,
			'time_taken'   => $userQuiz?->pivot->time_taken_seconds,
			'score'        => $userQuiz?->pivot->score,
			'completed_at' => $userQuiz?->pivot->updated_at,
		];
	}

	protected static function booted(): void
	{
		static::addGlobalScope('has_categories_and_questions', function (Builder $builder) {
			$builder->whereHas('categories')
				->whereHas('questions')
				->orderBy('quizzes.id', 'desc');
		});
	}

	public function scopeSimilarQuizzes(Builder $query, Quiz $quiz): Builder
	{
		$currentQuizCategories = $quiz->categories->pluck('id');

		return $query->whereHas('categories', function ($query) use ($currentQuizCategories, $quiz) {
			$query->whereIn('category_id', $currentQuizCategories);
		})
			->where('id', '!=', $quiz->id)
			->notCompleted()
			->with('categories', 'difficultyLevel')
			->withCount('users');
	}

	public function scopeNotCompleted(Builder $query): Builder
	{
		if (Auth::id()) {
			return $query->whereDoesntHave('users', function ($query) {
				$query->where('user_id', Auth::id())
					->whereNotNull('score');
			});
		}
		return $query;
	}

	public function scopeApplyFilterAndSort(Builder $query, array $filters): Builder
	{
		// if my quizzes and not-completed are both set and true then skip them
		if (!(isset($filters['my-quizzes']) && $filters['my-quizzes'] && isset($filters['not-completed']) && $filters['not-completed'])) {
			if (Auth::id() && isset($filters['my-quizzes']) && $filters['my-quizzes']) {
				$query->whereHas('users', function ($query) {
					$query->where('user_id', Auth::id())
						->whereNotNull('score');
				});
			}

			if (isset($filters['not-completed']) && $filters['not-completed']) {
				$query->notCompleted();
			}
		}

		if (isset($filters['search'])) {
			$query->where('title', 'like', '%' . $filters['search'] . '%');
		}

		if (isset($filters['difficulty-levels'])) {
			$query->whereIn('difficulty_level_id', $filters['difficulty-levels']);
		}

		if (isset($filters['categories'])) {
			$query->whereHas('categories', function ($query) use ($filters) {
				$query->whereIn('category_id', $filters['categories']);
			});
		}

		if (isset($filters['sort'])) {
			$query->orderBy($filters['sort'], $filters['order']);
		}

		return $query;
	}

	public function questions(): HasMany
	{
		return $this->hasMany(QuizQuestion::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}

	public function difficultyLevel(): BelongsTo
	{
		return $this->belongsTo(DifficultyLevel::class);
	}

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class)
			->withPivot('time_taken_seconds', 'score')
					->withTimestamps();
	}
}

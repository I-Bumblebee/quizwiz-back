<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
	use HasFactory;

	protected $fillable = ['option', 'is_correct', 'quiz_question_id'];

	protected $hidden = ['created_at', 'updated_at', 'quiz_question_id', 'is_correct'];

	public function quizQuestion(): BelongsTo
	{
		return $this->belongsTo(QuizQuestion::class);
	}
}

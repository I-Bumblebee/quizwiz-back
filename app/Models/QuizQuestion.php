<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
	use HasFactory;

	protected $fillable = ['question', 'points', 'quiz_id'];

	protected $hidden = ['created_at', 'updated_at', 'quiz_id'];

    protected $with = ['options'];
    protected $appends = ['correct_options_count'];

    public function getCorrectOptionsCountAttribute(): int
    {
        return $this->options->where('is_correct', true)->count();
    }

	public function options(): HasMany
	{
		return $this->hasMany(QuestionOption::class);
	}

	public function quiz(): BelongsTo
	{
		return $this->belongsTo(Quiz::class);
	}
}

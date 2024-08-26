<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QuizUser extends Pivot
{
	protected $table = 'quiz_user';

	public $incrementing = true;

	protected $fillable = ['user_id', 'quiz_id', 'time_taken_seconds', 'score'];
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class QuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                        => $this->id,
			'title'                     => $this->title,
			'image'                     => $this->image ? Storage::url($this->image) : Storage::url('images/quiz_default.jpg'),
			'short_info'                => $this->short_info,
			'description'               => $this->description,
			'time_limit_minutes'        => $this->time_limit_minutes,
			'number_of_questions'       => $this->number_of_questions,
			'total_points'              => $this->total_points,
			'categories'                => $this->categories,
			'difficulty_level'          => $this->difficultyLevel,
			'user'                      => $this->user,
			'users_count'               => $this->users()->count(),
		];
	}
}

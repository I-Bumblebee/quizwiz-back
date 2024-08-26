<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
	use HasFactory;

	protected $fillable = [
		'email',
		'tel',
		'facebook',
		'linkedin',
	];

	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
}

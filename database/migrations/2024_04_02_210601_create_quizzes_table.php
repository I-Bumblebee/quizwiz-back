<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quizzes', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('image')->nullable();
			$table->text('short_info');
			$table->text('description');
			$table->integer('time_limit_minutes');
			$table->integer('number_of_questions')->default(0);
			$table->integer('total_points')->default(0);
			$table->foreignId('difficulty_level_id')->constrained();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quizzes');
	}
};

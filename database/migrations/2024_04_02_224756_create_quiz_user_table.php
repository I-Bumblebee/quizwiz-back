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
		Schema::create('quiz_user', function (Blueprint $table) {
			$table->id();
			$table->integer('score');
			$table->integer('time_taken_seconds');
			$table->foreignId('quiz_id')->constrained();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quiz_user');
	}
};

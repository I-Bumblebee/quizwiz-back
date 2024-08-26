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
		Schema::table('quiz_user', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id')->nullable()->change();
			$table->integer('score')->default(0)->change();
			$table->integer('time_taken_seconds')->default(0)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('quiz_user', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id')->nullable(false)->change();
			$table->integer('score')->default(null)->change();
			$table->integer('time_taken_seconds')->default(null)->change();
		});
	}
};

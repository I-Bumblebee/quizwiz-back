<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactInfoController;
use App\Http\Controllers\DifficultyLevelController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/user', [UserController::class, 'user'])->name('user');
});

Route::post('/logout', [SessionController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
	Route::middleware('must-verify-email')->group(function () {
		Route::post('/login', [SessionController::class, 'login'])->name('login');
	});

	Route::post('/register', [SessionController::class, 'register'])->name('register');
	Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
	Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

	Route::prefix('email')->group(function () {
		Route::get('/verify/{user}/{hash}', [VerifyEmailController::class, 'verify'])->middleware('signed:relative')->name('verification.verify');
		Route::post('/verification-notification', [VerifyEmailController::class, 'sendVerificationEmail'])->name('verification.send');
	});
});

Route::prefix('quizzes')->group(function () {
	Route::get('/', [QuizController::class, 'index'])->name('quizzes.index');
	Route::get('/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
	Route::get('/{quiz}/questions', [QuizController::class, 'getQuizQuestions'])->name('quizzes.questions');
	Route::post('/{quiz}/start', [QuizController::class, 'startQuiz'])->middleware('ensure-quiz-not-taken')->name('quizzes.start');
	Route::post('/{quiz}/complete', [QuizController::class, 'completeQuiz'])->name('quizzes.completed');
	Route::get('/{quiz}/similar', [QuizController::class, 'similarQuizzes'])->name('quizzes.similar');
});

Route::prefix('categories')->group(function () {
	Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
});

Route::prefix('difficulty-levels')->group(function () {
	Route::get('/', [DifficultyLevelController::class, 'index'])->name('difficulty-levels.index');
});

Route::get('/contact-info', ContactInfoController::class)->name('contact-info.index');

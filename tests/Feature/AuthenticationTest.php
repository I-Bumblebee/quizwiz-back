<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmailNotification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function user_can_register()
	{
		Notification::fake();

		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'email'                 => 'test@example.com',
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(201);
		$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

		Notification::assertSentTo(
			[$user = User::first()],
			VerifyEmailNotification::class
		);
	}

	/** @test */
	public function user_can_verify_their_email()
	{
		Notification::fake();

		$user = User::factory()->create([
			'email_verified_at' => null,
		]);

		$user->notify(new VerifyEmailNotification());

		Notification::assertSentTo(
			[$user],
			VerifyEmailNotification::class,
			function ($notification, $channels) use ($user) {
				$verificationUrl = $notification->verificationUrl($user);
				$query = parse_url($verificationUrl, PHP_URL_QUERY);

				parse_str($query, $params);

				$verificationUrl = "http://localhost:8000/api/email/verify/{$params['user']}/{$params['hash']}?expires={$params['expires']}&signature={$params['signature']}";

				$this->assertNotNull($verificationUrl);

				$response = $this->getJson($verificationUrl);
				$response->assertStatus(200);

				$this->assertNotNull($user->fresh()->email_verified_at);

				return true;
			}
		);
	}

	/** @test */
	public function registration_requires_an_email()
	{
		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(422);
		$response->assertJsonValidationErrors('email');
	}

	/** @test */
	public function registration_requires_a_valid_email()
	{
		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'email'                 => 'not-an-email',
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(422);
		$response->assertJsonValidationErrors('email');
	}

	/** @test */
	public function registration_requires_a_password()
	{
		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'email'                 => 'test@example.com',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(422);
		$response->assertJsonValidationErrors('password');
	}

	/** @test */
	public function registration_requires_password_confirmation()
	{
		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'email'                 => 'test@example.com',
			'password'              => 'password',
			'password_confirmation' => 'different-password',
		]);

		$response->assertStatus(422);
		$response->assertJsonValidationErrors('password');
	}

	/** @test */
	public function registration_requires_unique_name()
	{
		$user = User::factory()->create(['name' => 'Test User']);

		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'email'                 => 'newuser@example.com',
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(422);
		$response->assertJsonValidationErrors('name');
	}

	/** @test */
	public function registration_requires_unique_email()
	{
		$user = User::factory()->create(['email' => 'test@example.com']);

		$response = $this->postJson('/api/register', [
			'name'                  => 'New User',
			'email'                 => 'test@example.com',
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(422);
		$response->assertJsonValidationErrors('email');
	}

	/** @test */
	public function only_guests_can_login()
	{
		$user = User::factory()->create();

		Auth::attempt(['email' => $user->email, 'password' => 'password']);

		$response = $this->postJson('/api/login', [
			'email'    => $user->email,
			'password' => 'password',
		]);

		$response->assertStatus(403);
	}

	/** @test */
	public function only_guests_can_register()
	{
		$user = User::factory()->create();

		Auth::login($user);

		$response = $this->postJson('/api/register', [
			'name'                  => 'Test User',
			'email'                 => 'test@example.com',
			'password'              => 'password',
			'password_confirmation' => 'password',
		]);

		$response->assertStatus(403);
	}
}

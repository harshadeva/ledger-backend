<?php

namespace Tests\Feature;

use App\Models\User;
use App\TestingTrait;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use TestingTrait;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        Artisan::call('migrate:fresh');

        // Install Passport
        Artisan::call('passport:client', [
            '--personal' => true,
        ]);
    }

    public function test_logs_in_successfully_with_valid_credentials()
    {
        // Arrange: Create a test user
        User::factory()->create([
            'email' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        // Act: Attempt to login
        $response = $this->postJson(route('login'), [
            'email' => 'testuser',
            'password' => 'password123',
        ]);

        // Assert: Verify the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user',
                'message',
            ]);

        // Assert: Check token generation
        $this->assertNotNull($response->json('token'));
    }

    public function test_fails_to_login_with_incorrect_password()
    {
        // Arrange: Create a test user
        User::factory()->create([
            'email' => 'testuser',
            'password' => Hash::make('password123'),
        ]);

        // Act: Attempt to login with the wrong password
        $response = $this->postJson(route('login'), [
            'email' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        // dd($response->json());

        // Assert: Verify the response
        $response->assertStatus(422)
            ->assertJsonStructure($this->getGenericErrorStructure());
    }

    public function test_fails_to_login_with_non_existent_email()
    {
        // Act: Attempt to login with a non-existent email
        $response = $this->postJson(route('login'), [
            'email' => 'nonexistentuser',
            'password' => 'password123',
        ]);

        // Assert: Verify the response
        $response->assertStatus(422)
                 ->assertJsonStructure($this->getGenericErrorStructure());
    }

    public function test_fails_to_login_when_email_is_missing()
    {
        // Act: Attempt to login without providing an email
        $response = $this->postJson(route('login'), [
            'password' => 'password123',
        ]);

        // Assert: Verify the validation error
        $response->assertStatus(422)
                 ->assertJsonStructure($this->getErrorStructure(['email']));
    }

    public function test_fails_to_login_when_password_is_missing()
    {
        // Act: Attempt to login without providing a password
        $response = $this->postJson(route('login'), [
            'email' => 'testuser',
        ]);

        // Assert: Verify the validation error
        $response->assertStatus(422)
        ->assertJsonStructure($this->getErrorStructure(['password']));
    }

    public function test_fails_to_login_with_invalid_input_data()
    {
        // Act: Attempt to login with invalid input
        $response = $this->postJson(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        // Assert: Verify the validation errors
        $response->assertStatus(422)
        ->assertJsonStructure($this->getErrorStructure(['email','password']));
    }
}

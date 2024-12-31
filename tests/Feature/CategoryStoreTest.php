<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\TestingTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryStoreTest extends TestCase
{
    use RefreshDatabase;
    use TestingTrait;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user for authentication
        $this->user = User::factory()->create();
    }

    public function test_creates_a_category_successfully_with_valid_data()
    {
        // Arrange: Valid category data
        $data = [
            'name' => 'New Category',
        ];

        // Act: Send POST request as an authenticated user
        $response = $this->actingAs($this->user, 'api')->postJson(route('categories.store'), $data);

        // Assert: Verify successful response and database entry
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Category saved']);

        $this->assertDatabaseHas('categories', ['name' => 'New Category', 'status' => 1]);
    }

    public function test_fails_to_create_a_category_when_name_is_missing()
    {
        // Arrange: Data without 'name'
        $data = [];

        // Act: Send POST request
        $response = $this->actingAs($this->user, 'api')->postJson(route('categories.store'), $data);

        // Assert: Validation failure
        $response->assertStatus(422)
                 ->assertJsonStructure($this->getErrorStructure(['name']));
    }


    public function test_fails_to_create_a_category_when_name_is_duplicate()
    {
        // Arrange: Create an existing category
        Category::create(['name' => 'Existing Category', 'status' => 1]);

        $data = [
            'name' => 'Existing Category',
        ];

        // Act: Send POST request with duplicate name
        $response = $this->actingAs($this->user, 'api')->postJson(route('categories.store'), $data);

        // Assert: Validation failure for unique constraint
        $response->assertStatus(422)
                 ->assertJsonStructure($this->getErrorStructure(['name']));
    }


    public function test_fails_to_create_a_category_when_name_exceeds_max_length()
    {
        // Arrange: Name with more than 255 characters
        $data = [
            'name' => str_repeat('A', 256),
        ];

        // Act: Send POST request
        $response = $this->actingAs($this->user, 'api')->postJson(route('categories.store'), $data);

        // Assert: Validation failure for max length
        $response->assertStatus(422)
                 ->assertJsonStructure($this->getErrorStructure(['name']));
    }


    public function test_handles_database_rollback_on_exception()
    {
        // Arrange: Mock Category model to throw an exception
        $this->mock(Category::class, function ($mock) {
            $mock->shouldReceive('create')->andThrow(new \Exception('Database Error'));
        });

        $data = [
            'name' => 'Rollback Category',
        ];

        // Act: Send POST request
        $response = $this->actingAs($this->user, 'api')->postJson(route('categories.store'), $data);

        // Assert: Verify generic error response
        $response->assertStatus(500)
                 ->assertJsonStructure($this->getGenericErrorStructure());

        $this->assertDatabaseMissing('categories', ['name' => 'Rollback Category']);
    }


    public function test_fails_to_create_category_for_unauthenticated_user()
    {
        // Arrange: Valid data
        $data = [
            'name' => 'Unauthenticated Category',
        ];

        // Act: Send POST request without authentication
        $response = $this->postJson(route('categories.store'), $data);

        // Assert: Unauthorized access
        $response->assertStatus(401)
                 ->assertJsonFragment(['message' => 'Unauthenticated.']);
    }
}

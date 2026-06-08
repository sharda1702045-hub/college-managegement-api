<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $staffUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions/roles before tests
        $this->seed();

        // Retrieve seeded users
        $this->adminUser = User::where('email', 'admin@example.com')->first();
        $this->staffUser = User::where('email', 'staff@example.com')->first();
    }

    public function test_unauthenticated_request_is_unauthorized(): void
    {
        $response = $this->getJson('/api/courses');
        $response->assertStatus(401);
    }

    public function test_staff_user_can_view_courses_list(): void
    {
        Course::create([
            'course_name' => 'Introduction to Computer Science',
            'course_code' => 'CS101',
            'description' => 'Basics of CS',
        ]);

        $response = $this->actingAs($this->staffUser)
            ->getJson('/api/courses');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'course_name', 'course_code', 'description'],
                ],
            ]);
    }

    public function test_staff_user_cannot_create_course(): void
    {
        $response = $this->actingAs($this->staffUser)
            ->postJson('/api/courses', [
                'course_name' => 'Data Structures',
                'course_code' => 'CS201',
                'description' => 'Intermediate DSA',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_user_can_create_course(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/courses', [
                'course_name' => 'Data Structures',
                'course_code' => 'CS201',
                'description' => 'Intermediate DSA',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Course created successfully',
            ]);

        $this->assertDatabaseHas('courses', ['course_code' => 'CS201']);
    }

    public function test_validation_fails_with_duplicate_course_code(): void
    {
        Course::create([
            'course_name' => 'Data Structures',
            'course_code' => 'CS201',
            'description' => 'DSA',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/courses', [
                'course_name' => 'Another DSA Class',
                'course_code' => 'CS201', // duplicate code
                'description' => 'DSA desc',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['course_code']);
    }

    public function test_admin_user_can_view_course_by_id(): void
    {
        $course = Course::create([
            'course_name' => 'Database Systems',
            'course_code' => 'CS301',
            'description' => 'MySQL/Postgres concepts',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->getJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $course->id,
                    'course_name' => 'Database Systems',
                ],
            ]);
    }

    public function test_course_not_found_returns_404(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/courses/999');

        $response->assertStatus(404);
    }

    public function test_admin_user_can_update_course(): void
    {
        $course = Course::create([
            'course_name' => 'Database Systems',
            'course_code' => 'CS301',
            'description' => 'MySQL/Postgres concepts',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->putJson("/api/courses/{$course->id}", [
                'course_name' => 'Advanced Database Systems',
                'course_code' => 'CS301', // same code allowed on update
                'description' => 'MySQL/Postgres/NoSQL concepts',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => [
                    'course_name' => 'Advanced Database Systems',
                ],
            ]);
    }

    public function test_admin_user_can_delete_course(): void
    {
        $course = Course::create([
            'course_name' => 'Database Systems',
            'course_code' => 'CS301',
            'description' => 'MySQL/Postgres concepts',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson("/api/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Course deleted successfully',
            ]);

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}

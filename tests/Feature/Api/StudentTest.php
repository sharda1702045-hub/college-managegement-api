<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
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
        $response = $this->getJson('/api/students');
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized request',
            ]);
    }

    public function test_staff_user_can_view_students_list(): void
    {
        Student::create([
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '2000-01-01',
        ]);

        $response = $this->actingAs($this->staffUser)
            ->getJson('/api/students');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'current_page',
                    'total_records',
                    'total_pages',
                    'data',
                ],
            ]);
    }

    public function test_staff_user_cannot_create_student(): void
    {
        $response = $this->actingAs($this->staffUser)
            ->postJson('/api/students', [
                'first_name' => 'Bob',
                'last_name' => 'Jones',
                'email' => 'bob@example.com',
                'phone' => '1234567890',
                'date_of_birth' => '1999-05-15',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Access denied: unauthorized role or permission',
            ]);
    }

    public function test_admin_user_can_create_student(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/students', [
                'first_name' => 'Bob',
                'last_name' => 'Jones',
                'email' => 'bob@example.com',
                'phone' => '1234567890',
                'date_of_birth' => '1999-05-15',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Student created successfully',
            ]);

        $this->assertDatabaseHas('students', ['email' => 'bob@example.com']);
    }

    public function test_validation_errors_for_student_creation(): void
    {
        // 1. Duplicate email
        Student::create([
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'duplicate@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '2000-01-01',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/students', [
                'first_name' => '', // required missing
                'last_name' => 'Jones',
                'email' => 'duplicate@example.com', // duplicate
                'phone' => 'invalid-phone-abc', // invalid format
                'date_of_birth' => '12-12-1999', // wrong format
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['first_name', 'email', 'phone', 'date_of_birth']);
    }

    public function test_admin_user_can_view_student_by_id(): void
    {
        $student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '1995-10-10',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->getJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $student->id,
                    'first_name' => 'John',
                ],
            ]);
    }

    public function test_student_not_found_returns_404(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/students/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Resource not found',
            ]);
    }

    public function test_admin_user_can_update_student(): void
    {
        $student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '1995-10-10',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->putJson("/api/students/{$student->id}", [
                'first_name' => 'Johnny',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com', // same email is allowed
                'phone' => '1234567890',
                'date_of_birth' => '1995-10-10',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => [
                    'first_name' => 'Johnny',
                ],
            ]);
    }

    public function test_admin_user_can_delete_student(): void
    {
        $student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '1995-10-10',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson("/api/students/{$student->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Student deleted successfully',
            ]);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_students_search(): void
    {
        Student::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'jsmith@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '1990-01-01',
        ]);

        Student::create([
            'first_name' => 'Bob',
            'last_name' => 'Doe',
            'email' => 'bob.doe@example.com',
            'phone' => '5556667777',
            'date_of_birth' => '1992-02-02',
        ]);

        // Search by first name
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/students?search=john');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
        $this->assertEquals('John', $response->json('data.data.0.first_name'));

        // Search by phone
        $response = $this->actingAs($this->adminUser)
            ->getJson('/api/students?search=5556667777');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
        $this->assertEquals('Bob', $response->json('data.data.0.first_name'));
    }
}

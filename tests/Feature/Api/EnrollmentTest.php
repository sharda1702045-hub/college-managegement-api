<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $staffUser;
    protected Student $student;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions/roles before tests
        $this->seed();

        // Retrieve seeded users
        $this->adminUser = User::where('email', 'admin@example.com')->first();
        $this->staffUser = User::where('email', 'staff@example.com')->first();

        // Create a student and a course for testing enrollments
        $this->student = Student::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '9876543210',
            'date_of_birth' => '1995-10-10',
        ]);

        $this->course = Course::create([
            'course_name' => 'Database Systems',
            'course_code' => 'CS301',
            'description' => 'MySQL/Postgres concepts',
        ]);
    }

    public function test_unauthenticated_request_is_unauthorized(): void
    {
        $response = $this->getJson('/api/enrollments');
        $response->assertStatus(401);
    }

    public function test_staff_user_can_view_enrollments_list(): void
    {
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->actingAs($this->staffUser)
            ->getJson('/api/enrollments');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'student_id',
                        'course_id',
                        'student' => ['id', 'first_name', 'last_name', 'email'],
                        'course' => ['id', 'course_name', 'course_code'],
                    ],
                ],
            ]);
    }

    public function test_staff_cannot_enroll_student(): void
    {
        $response = $this->actingAs($this->staffUser)
            ->postJson('/api/enrollments', [
                'student_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_enroll_student(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/enrollments', [
                'student_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Student enrolled successfully',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);
    }

    public function test_cannot_enroll_student_in_same_course_twice(): void
    {
        // 1. First enrollment
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        // 2. Try to enroll in the same course again
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/enrollments', [
                'student_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['student_id']);
    }

    public function test_enrollment_validation_fails_for_nonexistent_ids(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/enrollments', [
                'student_id' => 999, // non-existent student
                'course_id' => 999,  // non-existent course
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['student_id', 'course_id']);
    }

    public function test_admin_can_remove_enrollment(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson("/api/enrollments/{$enrollment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Enrollment removed successfully',
            ]);

        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }

    public function test_cascading_delete_removes_enrollments(): void
    {
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        // Delete the student
        $this->student->delete();

        // The enrollment record should be cascade-deleted
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }
}

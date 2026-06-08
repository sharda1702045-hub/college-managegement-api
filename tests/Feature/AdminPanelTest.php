<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $adminUser;
    protected User $staffUser;
    protected Student $student;
    protected Course $course;
    protected Enrollment $enrollment;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed Spatie roles and permissions
        $this->seed();

        $this->superAdmin = User::where('email', 'superadmin@example.com')->first();
        $this->adminUser = User::where('email', 'admin@example.com')->first();
        $this->staffUser = User::where('email', 'staff@example.com')->first();

        // Setup base database entities for routes mapping
        $this->student = Student::create([
            'id' => 1,
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '2000-01-01',
        ]);

        $this->course = Course::create([
            'id' => 1,
            'course_name' => 'Web Design',
            'course_code' => 'CS101',
            'description' => 'Frontend coding',
        ]);

        $this->enrollment = Enrollment::create([
            'id' => 1,
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $this->otherUser = User::create([
            'id' => 10,
            'name' => 'Other Admin',
            'email' => 'other@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    /**
     * Test guest redirect to login for all protected routes.
     */
    public function test_guest_is_redirected_to_login_on_all_protected_routes(): void
    {
        $routes = [
            ['GET', '/admin/dashboard'],
            ['GET', '/admin/students'],
            ['GET', '/admin/students/create'],
            ['POST', '/admin/students'],
            ['GET', '/admin/students/1'],
            ['GET', '/admin/students/1/edit'],
            ['PUT', '/admin/students/1'],
            ['DELETE', '/admin/students/1'],
            ['GET', '/admin/courses'],
            ['GET', '/admin/courses/create'],
            ['POST', '/admin/courses'],
            ['GET', '/admin/courses/1'],
            ['GET', '/admin/courses/1/edit'],
            ['PUT', '/admin/courses/1'],
            ['DELETE', '/admin/courses/1'],
            ['GET', '/admin/enrollments'],
            ['GET', '/admin/enrollments/create'],
            ['POST', '/admin/enrollments'],
            ['DELETE', '/admin/enrollments/1'],
            ['GET', '/admin/admins'],
            ['GET', '/admin/admins/create'],
            ['POST', '/admin/admins'],
            ['GET', '/admin/admins/' . $this->otherUser->id . '/edit'],
            ['PUT', '/admin/admins/' . $this->otherUser->id],
            ['DELETE', '/admin/admins/' . $this->otherUser->id],
            ['GET', '/admin/roles'],
            ['GET', '/admin/permissions'],
        ];

        foreach ($routes as $route) {
            [$method, $url] = $route;
            
            $response = $this->call($method, $url);
            $response->assertRedirect('/admin/login');
        }
    }

    /**
     * Test staff role restrictions on all modification routes (403).
     */
    public function test_staff_user_is_forbidden_on_modification_routes(): void
    {
        $forbiddenRoutes = [
            ['GET', '/admin/students/create'],
            ['POST', '/admin/students'],
            ['GET', '/admin/students/1/edit'],
            ['PUT', '/admin/students/1'],
            ['DELETE', '/admin/students/1'],
            ['GET', '/admin/courses/create'],
            ['POST', '/admin/courses'],
            ['GET', '/admin/courses/1/edit'],
            ['PUT', '/admin/courses/1'],
            ['DELETE', '/admin/courses/1'],
            ['GET', '/admin/enrollments/create'],
            ['POST', '/admin/enrollments'],
            ['DELETE', '/admin/enrollments/1'],
            ['GET', '/admin/admins'],
            ['GET', '/admin/admins/create'],
            ['POST', '/admin/admins'],
            ['GET', '/admin/admins/' . $this->otherUser->id . '/edit'],
            ['PUT', '/admin/admins/' . $this->otherUser->id],
            ['DELETE', '/admin/admins/' . $this->otherUser->id],
            ['GET', '/admin/roles'],
            ['GET', '/admin/permissions'],
        ];

        foreach ($forbiddenRoutes as $route) {
            [$method, $url] = $route;
            
            $response = $this->actingAs($this->staffUser, 'web')->call($method, $url);
            $response->assertStatus(403);
        }
    }

    /**
     * Test staff user can view read-only index routes.
     */
    public function test_staff_user_can_access_view_only_routes(): void
    {
        $allowedRoutes = [
            '/admin/dashboard',
            '/admin/students',
            '/admin/students/1',
            '/admin/courses',
            '/admin/courses/1',
            '/admin/enrollments',
        ];

        foreach ($allowedRoutes as $url) {
            $response = $this->actingAs($this->staffUser, 'web')->get($url);
            $response->assertStatus(200);
        }
    }

    /**
     * Test super admin user can access all panel routes successfully.
     */
    public function test_super_admin_has_full_panel_access(): void
    {
        $routes = [
            '/admin/dashboard',
            '/admin/students',
            '/admin/students/create',
            '/admin/students/1',
            '/admin/students/1/edit',
            '/admin/courses',
            '/admin/courses/create',
            '/admin/courses/1',
            '/admin/courses/1/edit',
            '/admin/enrollments',
            '/admin/enrollments/create',
            '/admin/admins',
            '/admin/admins/create',
            '/admin/admins/' . $this->otherUser->id . '/edit',
            '/admin/roles',
            '/admin/permissions',
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($this->superAdmin, 'web')->get($url);
            $response->assertStatus(200);
        }
    }

    /**
     * Duplicate Student Validation (email).
     */
    public function test_student_duplicate_email_validation_error(): void
    {
        // Assert creation fails with duplicate email
        $response = $this->actingAs($this->superAdmin, 'web')
            ->from('/admin/students/create')
            ->post('/admin/students', [
                'first_name' => 'Bob',
                'last_name' => 'Builder',
                'email' => 'alice@example.com', // Duplicate email
                'phone' => '0987654321',
                'date_of_birth' => '1998-05-05',
            ]);

        $response->assertRedirect('/admin/students/create');
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Duplicate Course Validation (course_code).
     */
    public function test_course_duplicate_code_validation_error(): void
    {
        // Assert creation fails with duplicate course code
        $response = $this->actingAs($this->superAdmin, 'web')
            ->from('/admin/courses/create')
            ->post('/admin/courses', [
                'course_name' => 'Frontend Basics',
                'course_code' => 'CS101', // Duplicate course code
                'description' => 'CSS and HTML',
            ]);

        $response->assertRedirect('/admin/courses/create');
        $response->assertSessionHasErrors(['course_code']);
    }

    /**
     * Duplicate Enrollment Validation.
     */
    public function test_enrollment_duplicate_entry_validation_error(): void
    {
        // Assert duplicate enrollment fails
        $response = $this->actingAs($this->superAdmin, 'web')
            ->from('/admin/enrollments/create')
            ->post('/admin/enrollments', [
                'student_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $response->assertRedirect('/admin/enrollments/create');
        $response->assertSessionHasErrors(['student_id']);
    }

    /**
     * Sidebar module visibility based on permissions.
     */
    public function test_sidebar_links_visibility_based_on_permissions(): void
    {
        // Staff user view
        $response = $this->actingAs($this->staffUser, 'web')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Students');
        $response->assertSee('Courses');
        $response->assertSee('Enrollments');
        $response->assertDontSee('admin/admins');
        $response->assertDontSee('admin/roles');
        $response->assertDontSee('admin/permissions');

        // Super Admin view
        $response = $this->actingAs($this->superAdmin, 'web')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Students');
        $response->assertSee('Courses');
        $response->assertSee('Enrollments');
        $response->assertSee('admin/admins');
        $response->assertSee('admin/roles');
        $response->assertSee('admin/permissions');
    }
}

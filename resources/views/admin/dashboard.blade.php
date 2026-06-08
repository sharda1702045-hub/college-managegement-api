@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')

    <!-- Dashboard Metrics Cards -->
    <div class="row g-4 mb-4">
        
        <!-- Total Students Card -->
        @canany(['view-students', 'students.*'])
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card p-3 border-0 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Students</span>
                        <h2 class="fw-bold mb-0 text-slate-800">{{ $totalStudents }}</h2>
                    </div>
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.students.index') }}" class="small text-decoration-none fw-semibold">View Students List <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
                </div>
            </div>
        </div>
        @endcanany

        <!-- Total Courses Card -->
        @canany(['view-courses', 'courses.*'])
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card p-3 border-0 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Courses</span>
                        <h2 class="fw-bold mb-0 text-slate-800">{{ $totalCourses }}</h2>
                    </div>
                    <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.courses.index') }}" class="small text-decoration-none fw-semibold text-success">View Courses List <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
                </div>
            </div>
        </div>
        @endcanany

        <!-- Total Enrollments Card -->
        @canany(['view-enrollments', 'enrollments.*'])
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card p-3 border-0 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total Enrollments</span>
                        <h2 class="fw-bold mb-0 text-slate-800">{{ $totalEnrollments }}</h2>
                    </div>
                    <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.enrollments.index') }}" class="small text-decoration-none fw-semibold text-warning">View Enrollments List <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
                </div>
            </div>
        </div>
        @endcanany

    </div>

    <!-- Quick Navigation Cards -->
    @canany(['manage-students', 'students.*', 'manage-courses', 'courses.*', 'manage-enrollments', 'enrollments.*', 'admins.*'])
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-rocket text-primary me-2"></i>Quick Actions</h5>
                <div class="d-flex flex-wrap gap-3">
                    @canany(['manage-students', 'students.*'])
                    <a href="{{ route('admin.students.create') }}" class="btn btn-outline-primary d-flex align-items-center gap-2 py-2 px-3">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Register New Student</span>
                    </a>
                    @endcanany
                    
                    @canany(['manage-courses', 'courses.*'])
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-success d-flex align-items-center gap-2 py-2 px-3">
                        <i class="fa-solid fa-folder-plus"></i>
                        <span>Create New Course</span>
                    </a>
                    @endcanany
                    
                    @canany(['manage-enrollments', 'enrollments.*'])
                    <a href="{{ route('admin.enrollments.create') }}" class="btn btn-outline-warning d-flex align-items-center gap-2 py-2 px-3">
                        <i class="fa-solid fa-signature"></i>
                        <span>Enroll Student in Course</span>
                    </a>
                    @endcanany
                    
                    @can('admins.*')
                    <a href="{{ route('admin.admins.create') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 px-3">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Add Admin / User</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @endcanany

    <div class="row g-4">
        
        <!-- Latest Students Table -->
        @canany(['view-students', 'students.*'])
        <div class="col-12 col-xl-6">
            <div class="card custom-table-card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0 text-slate-800">Latest Registered Students</h5>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestStudents as $student)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                        <div class="text-muted small">DOB: {{ $student->date_of_birth }}</div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>
                                        <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-icon btn-light" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endcanany

        <!-- Latest Enrollments Table -->
        @canany(['view-enrollments', 'enrollments.*'])
        <div class="col-12 col-xl-6">
            <div class="card custom-table-card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0 text-slate-800">Recent Course Enrollments</h5>
                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestEnrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $enrollment->student?->first_name }} {{ $enrollment->student?->last_name }}</div>
                                        <div class="text-muted small">{{ $enrollment->student?->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary badge-premium">{{ $enrollment->course?->course_code }}</span>
                                        <span class="d-block text-truncate small" style="max-width: 150px;">{{ $enrollment->course?->course_name }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $enrollment->created_at?->format('Y-m-d H:i') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No enrollments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endcanany

    </div>

@endsection

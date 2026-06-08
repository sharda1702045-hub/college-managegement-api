@extends('admin.layouts.app')

@section('title', 'Student Details')
@section('page_title', 'Student Profile')

@section('content')

    <div class="row">
        
        <!-- Student Info Card -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-icon btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    <h5 class="fw-bold mb-0 text-slate-800">Student Profile</h5>
                </div>
                
                <div class="card-body p-4 text-center border-top">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2.25rem;">
                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ $student->first_name }} {{ $student->last_name }}</h4>
                    <p class="text-muted small mb-4">ID: #{{ $student->id }}</p>
                    
                    <div class="text-start border-top pt-3">
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Email Address</span>
                            <span class="fw-semibold">{{ $student->email }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block mb-1">Phone Number</span>
                            <span class="fw-semibold">{{ $student->phone }}</span>
                        </div>
                        <div class="mb-0">
                            <span class="text-muted small d-block mb-1">Date of Birth</span>
                            <span class="fw-semibold">{{ $student->date_of_birth }}</span>
                        </div>
                    </div>
                    
                    @can('manage-students')
                        <div class="d-grid gap-2 border-top mt-4 pt-3">
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit Profile</span>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
        
        <!-- Student Enrollments Card -->
        <div class="col-12 col-lg-8">
            <div class="card custom-table-card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-book-open text-success me-2"></i>Active Course Enrollments</h5>
                    @can('manage-enrollments')
                        <a href="{{ route('admin.enrollments.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Enrollment</span>
                        </a>
                    @endcan
                </div>
                
                <div class="table-responsive border-top">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Enrolled At</th>
                                @can('manage-enrollments')
                                    <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary badge-premium">{{ $enrollment->course?->course_code }}</span>
                                    </td>
                                    <td class="fw-bold">{{ $enrollment->course?->course_name }}</td>
                                    <td class="text-muted small">{{ $enrollment->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    @can('manage-enrollments')
                                        <td>
                                            <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this student from the course?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-light" title="Remove Enrollment">
                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-regular fa-folder-open d-block fs-1 mb-3"></i>
                                        <span>This student is not enrolled in any courses.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@extends('admin.layouts.app')

@section('title', 'Course Details')
@section('page_title', 'Course Details')

@section('content')

    <div class="row">
        
        <!-- Course Info Card -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center gap-2">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-icon btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    <h5 class="fw-bold mb-0 text-slate-800">Course Information</h5>
                </div>
                
                <div class="card-body p-4 border-top">
                    <div class="mb-4 text-center">
                        <span class="badge bg-primary-subtle text-primary badge-premium fs-5 px-3 py-2 mb-2">{{ $course->course_code }}</span>
                        <h4 class="fw-bold mb-1">{{ $course->course_name }}</h4>
                    </div>
                    
                    <div class="text-start border-top pt-3">
                        <span class="text-muted small d-block mb-2 text-uppercase fw-semibold">Course Description</span>
                        <p class="text-slate-700 bg-light p-3 rounded-3" style="white-space: pre-wrap; font-size: 0.9rem; line-height: 1.5;">{{ $course->description ?? 'No description provided for this course.' }}</p>
                    </div>
                    
                    @can('manage-courses')
                        <div class="d-grid gap-2 border-top mt-4 pt-3">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit Course Details</span>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
        
        <!-- Enrolled Students Card -->
        <div class="col-12 col-lg-8">
            <div class="card custom-table-card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-user-graduate text-success me-2"></i>Enrolled Students</h5>
                    @can('manage-enrollments')
                        <a href="{{ route('admin.enrollments.create', ['course_id' => $course->id]) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                            <i class="fa-solid fa-plus"></i>
                            <span>Enroll Student</span>
                        </a>
                    @endcan
                </div>
                
                <div class="table-responsive border-top">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrolled At</th>
                                @can('manage-enrollments')
                                    <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->enrollments as $enrollment)
                                <tr>
                                    <td class="fw-bold">{{ $enrollment->student?->first_name }} {{ $enrollment->student?->last_name }}</td>
                                    <td>{{ $enrollment->student?->email }}</td>
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
                                        <span>No students are enrolled in this course yet.</span>
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

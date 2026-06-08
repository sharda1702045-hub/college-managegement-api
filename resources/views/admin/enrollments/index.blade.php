@extends('admin.layouts.app')

@section('title', 'Enrollments')
@section('page_title', 'Enrollment Management')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-id-card text-primary me-2"></i>Enrollment List</h5>
            @can('manage-enrollments')
                <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Enroll Student</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Enrollments Table Card -->
    <div class="card custom-table-card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Enrollment Date</th>
                        @can('manage-enrollments')
                            <th>Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        <tr>
                            <td class="fw-bold">
                                <a href="{{ route('admin.students.show', $enrollment->student?->id ?? 0) }}" class="text-decoration-none">
                                    {{ $enrollment->student?->first_name }} {{ $enrollment->student?->last_name }}
                                </a>
                            </td>
                            <td>{{ $enrollment->student?->email }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary badge-premium fs-6">
                                    {{ $enrollment->course?->course_code }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.show', $enrollment->course?->id ?? 0) }}" class="text-decoration-none text-dark">
                                    {{ $enrollment->course?->course_name }}
                                </a>
                            </td>
                            <td class="text-muted small">
                                {{ $enrollment->created_at?->format('Y-m-d H:i') ?? 'N/A' }}
                            </td>
                            @can('manage-enrollments')
                                <td>
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this student enrollment?');">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open d-block fs-1 mb-3"></i>
                                <span>No course enrollments found.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

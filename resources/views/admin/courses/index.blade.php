@extends('admin.layouts.app')

@section('title', 'Courses')
@section('page_title', 'Course Management')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-book-open text-primary me-2"></i>Courses List</h5>
            @can('manage-courses')
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Course</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Search Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.courses.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-9 col-lg-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by course code or name..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-secondary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Table Card -->
    <div class="card custom-table-card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 15%;">Course Code</th>
                        <th style="width: 35%;">Course Name</th>
                        <th style="width: 35%;">Description</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary badge-premium fs-6">{{ $course->course_code }}</span>
                            </td>
                            <td class="fw-bold">{{ $course->course_name }}</td>
                            <td class="text-muted text-truncate" style="max-width: 300px;">{{ $course->description ?? 'No description provided.' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-icon btn-light" title="View Enrolled Students">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>
                                    
                                    @can('manage-courses')
                                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-icon btn-light" title="Edit Course">
                                            <i class="fa-solid fa-pen-to-square text-success"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course and all its enrollments?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light" title="Delete Course">
                                                <i class="fa-solid fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open d-block fs-1 mb-3"></i>
                                <span>No courses found.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="card-footer bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $courses->firstItem() ?? 0 }} to {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} records
                </div>
                <div>
                    {{ $courses->appends(['search' => $search])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

@endsection

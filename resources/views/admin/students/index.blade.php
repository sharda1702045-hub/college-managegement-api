@extends('admin.layouts.app')

@section('title', 'Students')
@section('page_title', 'Student Management')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-user-graduate text-primary me-2"></i>Students List</h5>
            @can('manage-students')
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Register Student</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Search Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.students.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-9 col-lg-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, or phone..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-secondary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table Card -->
    <div class="card custom-table-card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>DOB</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ $student->date_of_birth }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-icon btn-light" title="View Details">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>
                                    
                                    @can('manage-students')
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-icon btn-light" title="Edit Student">
                                            <i class="fa-solid fa-pen-to-square text-success"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student and all their enrollments?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light" title="Delete Student">
                                                <i class="fa-solid fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open d-block fs-1 mb-3"></i>
                                <span>No students found.</span>
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
                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} records
                </div>
                <div>
                    {{ $students->appends(['search' => $search])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

@endsection

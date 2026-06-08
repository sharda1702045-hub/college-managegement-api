@extends('admin.layouts.app')

@section('title', 'Admins')
@section('page_title', 'Admin & Staff Management')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-user-shield text-primary me-2"></i>Admin Users List</h5>
            @can('admins.*')
                <a href="{{ route('admin.admins.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add User</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Search Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.admins.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-9 col-lg-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or email..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-secondary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admins Table Card -->
    <div class="card custom-table-card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Assigned Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td class="fw-bold">
                                {{ $admin->name }}
                                @if(auth('web')->id() === $admin->id)
                                    <span class="badge bg-primary-subtle text-primary badge-premium ms-1">You</span>
                                @endif
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @foreach($admin->roles as $role)
                                    <span class="badge bg-info-subtle text-info badge-premium">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @can('admins.*')
                                        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-sm btn-icon btn-light" title="Edit Admin">
                                            <i class="fa-solid fa-pen-to-square text-success"></i>
                                        </a>
                                        
                                        @if(auth('web')->id() !== $admin->id)
                                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin/staff user?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-light" title="Delete Admin">
                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted small">No permissions</span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open d-block fs-1 mb-3"></i>
                                <span>No users found.</span>
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
                    Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of {{ $admins->total() }} records
                </div>
                <div>
                    {{ $admins->appends(['search' => $search])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

@endsection

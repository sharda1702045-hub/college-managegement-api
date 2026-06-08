@extends('admin.layouts.app')

@section('title', 'Roles')
@section('page_title', 'Role-Based Access Control')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-users-gear text-primary me-2"></i>User Roles</h5>
            <p class="text-muted small mb-0">List of system roles and their assigned permissions</p>
        </div>
    </div>

    <!-- Roles List Card -->
    <div class="card custom-table-card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 25%;">Role Name</th>
                        <th style="width: 75%;">Assigned Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td class="fw-bold fs-6">
                                <span class="badge bg-primary-subtle text-primary badge-premium fs-6 py-2 px-3">{{ $role->name }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 py-1">
                                    @forelse($role->permissions as $permission)
                                        <span class="badge bg-secondary-subtle text-secondary badge-premium">{{ $permission->name }}</span>
                                    @empty
                                        <span class="text-muted small">No permissions assigned.</span>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

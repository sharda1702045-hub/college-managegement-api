@extends('admin.layouts.app')

@section('title', 'Permissions')
@section('page_title', 'System Permissions')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-key text-primary me-2"></i>Permissions List</h5>
            <p class="text-muted small mb-0">List of all system permissions registered with the Spatie driver</p>
        </div>
    </div>

    <!-- Permissions List Card -->
    <div class="card custom-table-card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 45%;">Permission Name</th>
                        <th style="width: 45%;">Guard Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>#{{ $permission->id }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary badge-premium fs-6">{{ $permission->name }}</span>
                            </td>
                            <td class="text-muted">{{ $permission->guard_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="card-footer bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $permissions->firstItem() ?? 0 }} to {{ $permissions->lastItem() ?? 0 }} of {{ $permissions->total() }} records
                </div>
                <div>
                    {{ $permissions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

@endsection

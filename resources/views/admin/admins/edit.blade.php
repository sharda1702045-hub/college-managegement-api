@extends('admin.layouts.app')

@section('title', 'Edit Admin User')
@section('page_title', 'Edit Admin/Staff User')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center gap-2">
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-icon btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    <h5 class="fw-bold mb-0 text-slate-800">Edit User Details: {{ $admin->name }}</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-semibold text-secondary">Full Name *</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">Email Address *</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label small fw-semibold text-secondary">Password (Leave blank to keep current)</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary d-block">Assign Roles *</label>
                            <div class="card p-3 border-light-subtle bg-light-subtle">
                                <div class="d-flex flex-wrap gap-4">
                                    @foreach($roles as $role)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" {{ in_array($role->name, old('roles', $assignedRoles)) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="role_{{ $role->id }}">
                                                <span class="fw-semibold">{{ $role->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('roles')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

@endsection

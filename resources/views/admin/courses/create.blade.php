@extends('admin.layouts.app')

@section('title', 'Create Course')
@section('page_title', 'Create New Course')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center gap-2">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-icon btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    <h5 class="fw-bold mb-0 text-slate-800">Course Details</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.courses.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="course_name" class="form-label small fw-semibold text-secondary">Course Name *</label>
                            <input type="text" name="course_name" id="course_name" class="form-control @error('course_name') is-invalid @enderror" placeholder="Data Structures and Algorithms" value="{{ old('course_name') }}" required>
                            @error('course_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="course_code" class="form-label small fw-semibold text-secondary">Course Code *</label>
                            <input type="text" name="course_code" id="course_code" class="form-control @error('course_code') is-invalid @enderror" placeholder="CS201" value="{{ old('course_code') }}" required>
                            <div class="form-text small">Must be unique and not exceed 100 characters.</div>
                            @error('course_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label small fw-semibold text-secondary">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Enter course description here...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Create Course</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

@endsection

@extends('admin.layouts.app')

@section('title', 'Enroll Student')
@section('page_title', 'Enroll Student in Course')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center gap-2">
                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-icon btn-light"><i class="fa-solid fa-arrow-left"></i></a>
                    <h5 class="fw-bold mb-0 text-slate-800">Enrollment Form</h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.enrollments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="student_id" class="form-label small fw-semibold text-secondary">Select Student *</label>
                            <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Choose a Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $selectedStudentId) == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="course_id" class="form-label small fw-semibold text-secondary">Select Course *</label>
                            <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                <option value="">-- Choose a Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                                        [{{ $course->course_code }}] {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('admin.enrollments.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Enroll Student</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

@endsection

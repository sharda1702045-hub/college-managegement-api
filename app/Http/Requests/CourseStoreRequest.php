<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $courseId = $this->route('course'); // Retrieve route parameter for updates

        return [
            'course_name' => ['required', 'string', 'max:255'],
            'course_code' => [
                'required',
                'string',
                'max:100',
                'unique:courses,course_code,' . ($courseId ?? 'NULL') . ',id'
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}

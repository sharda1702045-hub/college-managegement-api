<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
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
        $studentId = $this->route('student'); // Retrieve the student ID from route for PUT requests

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                'unique:students,email,' . ($studentId ?? 'NULL') . ',id'
            ],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-()]{10,20}$/'],
            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number format is invalid. It must be a valid phone format with 10 to 20 digits.',
            'date_of_birth.date_format' => 'The date of birth must be in Y-m-d format.',
        ];
    }
}

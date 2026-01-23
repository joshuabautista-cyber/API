<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'       => 'required|integer|exists:tbl_users,user_id',
            'semester_id'   => 'required|integer|exists:tbl_semester,semester_id',
            'course_id'     => 'required|integer|exists:tbl_course,course_id',
            'section'       => 'required|string|max:255',
        ];
    }
}

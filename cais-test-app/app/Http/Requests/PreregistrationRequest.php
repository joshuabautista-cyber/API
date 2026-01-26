<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreregistrationRequest extends FormRequest
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
        $rules = [
            'user_id' => 'required|integer|exists:tbl_users,user_id',
            'semester_id' => 'required|integer|exists:tbl_semester,semester_id',
            'course_id' => 'required|integer|exists:tbl_course,course_id',
            'schedId' => 'required|integer|exists:tbl_class_schedules,schedId',
            'section' => 'required|string|max:50',
            'subject_code' => 'nullable|string|max:50',
            'subject_title' => 'nullable|string|max:255',
            'units' => 'nullable|integer|min:0',
            'status' => 'nullable|in:pending,enrolled,cancelled',
        ];

        // On update, make fields optional
        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            $rules = array_map(function ($rule) {
                return str_replace('required|', 'sometimes|', $rule);
            }, $rules);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User not found',
            'semester_id.required' => 'Semester ID is required',
            'semester_id.exists' => 'Semester not found',
            'course_id.required' => 'Course ID is required',
            'course_id.exists' => 'Course not found',
            'schedId.required' => 'Schedule ID is required',
            'schedId.exists' => 'Class schedule not found',
            'section.required' => 'Section is required',
        ];
    }
}

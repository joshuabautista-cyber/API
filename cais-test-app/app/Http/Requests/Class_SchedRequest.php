<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Class_SchedRequest extends FormRequest
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
           'semester_id' => 'required|integer|exists:tbl_semester,semester_id',
           'subject_code' => 'required|string|max:255',
           'course_id' => 'required|integer|exists:tbl_course,course_id',
           'cat_no' => 'required|string|max:255',
           'subject_title' => 'required|string|max:255',
           'units' => 'required|integer|max:1',
            'atl' => 'required|string|max:255',
            'time' => 'required|time|max:255',
            'day' => 'required|string',
            'section' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'slot_no' => 'required|string|max:255',
            'class_type' => 'required|string|max:255',
            'lab_type' => 'required|string|max:255',
            'dept_id' => 'required|integer|exists:tbl_department,department_id',
            'weight' => 'required|string|max:255',
            'bulk_upload' => 'required|string|max:255'
        ];
    }
}

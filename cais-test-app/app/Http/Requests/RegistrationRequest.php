<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Changed to true, otherwise all requests will return "403 Forbidden"
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
            'user_id' => 'required|integer|exists:tbl_users,user_id',
            'schedId' => 'required|integer|exists:tbl_class_schedules,schedId',
            'enrollment_id' => 'required|integer|exists:tbl_enrollments,enrollment_id',
            
            // Optional fields based on your fillable array
            'enrollment_type' => 'nullable|string|max:50',
            'ra_status' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'ra_date_approved' => 'nullable|date',
            'forced_drop' => 'boolean',
        ];
    }
}
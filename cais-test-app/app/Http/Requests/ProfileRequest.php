<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
        // 1. Try to get the ID from the route.
        // It checks if the URL param is named 'profile' OR 'id'.
        $profile = $this->route('profile') ?? $this->route('id');

        // 2. Extract the actual numeric ID (handles Model Binding vs Raw ID)
        $profileId = $profile instanceof \App\Models\Profile ? $profile->profile_id : $profile;

        return [
            'mname'         => 'required|string|max:255',
            'fname'         => 'required|string|max:255',
            'lname'         => 'required|string|max:255',
            'birthdate'     => 'required|date',
            'sex'           => 'required|string|max:10',
            'address'       => 'required|string|max:500',
            'contact'       => 'required|string|max:20', 
            
            // 3. IGNORE RULE: We pass the found $profileId here
            'contact_email' => [
                'required', 
                'email', 
                \Illuminate\Validation\Rule::unique('tbl_profile', 'contact_email')->ignore($profileId, 'profile_id')
            ],
            
            'p_email'       => [
                'required', 
                'email', 
                \Illuminate\Validation\Rule::unique('tbl_profile', 'p_email')->ignore($profileId, 'profile_id')
            ],

            'dept_id'       => 'required|exists:tbl_department,dept_id',
            'college_id'    => 'required|exists:tbl_college,college_id',
            'course_id'     => 'nullable|exists:tbl_course,course_id',
        ];
    }
}
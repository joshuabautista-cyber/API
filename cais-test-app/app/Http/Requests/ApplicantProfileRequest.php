<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantProfileRequest extends FormRequest
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
        $rules = [
            // Basic Information
            'firstname' => 'nullable|string|max:50',
            'middlename' => 'nullable|string|max:50',
            'lastname' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:11',
            'student_mobile_contact' => 'nullable|string|max:20',
            'student_email' => 'nullable|email|max:50',
            'course_program' => 'nullable|string|max:255',
            
            // Personal Details
            'civil_status' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|string|max:50',
            'place_of_birth' => 'nullable|string',
            'nationality' => 'nullable|string|max:100',
            'sex' => 'nullable|string|max:10',
            'gender' => 'nullable|string|max:50',
            'religion_id' => 'nullable|integer',
            'birth_order' => 'nullable|string',
            'sibling_in_college' => 'nullable|string|max:11',
            'sibling_college_graduate' => 'nullable|string|max:11',
            
            // Address Information
            'permanent_address' => 'nullable|string',
            'permanent_cluster' => 'nullable|string',
            'zipcode' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'clsu_address' => 'nullable|string',
            'clsu_cluster' => 'nullable|string',
            'clsu_zipcode' => 'nullable|string|max:11',
            'clsu_country' => 'nullable|string|max:100',
            
            // Family Background
            'father_fname' => 'nullable|string|max:50',
            'father_mname' => 'nullable|string|max:50',
            'father_lname' => 'nullable|string|max:50',
            'father_contact' => 'nullable|string|max:50',
            'father_education' => 'nullable|string|max:100',
            'father_occupation' => 'nullable|string',
            'mother_fname' => 'nullable|string|max:50',
            'mother_mname' => 'nullable|string|max:50',
            'mother_lname' => 'nullable|string|max:50',
            'mother_contact' => 'nullable|string|max:50',
            'mother_education' => 'nullable|string',
            'mother_occupation' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:50',
            'guardian_contact' => 'nullable|string|max:20',
            'emergency_person' => 'nullable|string|max:50',
            'emergency_contact' => 'nullable|string|max:20',
            
            // Academic Background
            'elementary_school_address' => 'nullable|string',
            'elementary_year' => 'nullable|string',
            'e_address' => 'nullable|string',
            'high_school_address' => 'nullable|string',
            'high_school_year' => 'nullable|string',
            'h_address' => 'nullable|string',
            'senior_high_address' => 'nullable|string',
            'senior_high_year' => 'nullable|string|max:30',
            'sh_address' => 'nullable|string',
            
            // PWD/Disability
            'disability' => 'nullable|integer',
            'disability_type' => 'nullable|string',
            'disability_proof' => 'nullable|string',
            
            // Indigenous/Minority
            'indigenous' => 'nullable|integer',
            'indigenous_type' => 'nullable|string',
            'indigenous_proof' => 'nullable|string',
            
            // Family Income
            'family_income' => 'nullable|string|max:100',
            'itr' => 'nullable|string',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'student_mobile_contact.required' => 'Contact number is required.',
            'student_email.required' => 'Email is required.',
            'student_email.email' => 'Please enter a valid email address.',
            'civil_status.required' => 'Civil status is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'place_of_birth.required' => 'Place of birth is required.',
            'nationality.required' => 'Nationality is required.',
            'sex.required' => 'Sex is required.',
            'religion_id.required' => 'Religion is required.',
            'permanent_address.required' => 'Permanent address is required.',
            'family_income.required' => 'Family income is required.',
        ];
    }
}

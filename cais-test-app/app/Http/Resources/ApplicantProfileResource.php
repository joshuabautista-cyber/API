<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'applicant_id' => $this->applicant_id,
            'user_id' => $this->user_id,
            
            // Basic Information
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'suffix' => $this->suffix,
            'age' => $this->age,
            'student_mobile_contact' => $this->student_mobile_contact,
            'student_tel_contact' => $this->student_tel_contact,
            'student_email' => $this->student_email,
            'course_program' => $this->course_program,
            
            // Personal Details
            'sex' => $this->sex,
            'gender' => $this->gender,
            'civil_status' => $this->civil_status,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'nationality' => $this->nationality,
            'religion_id' => $this->religion_id,
            'citizenship_id' => $this->citizenship_id,
            'birth_order' => $this->birth_order,
            'sibling_in_college' => $this->sibling_in_college,
            'sibling_college_graduate' => $this->sibling_college_graduate,
            
            // Address Information
            'permanent_address' => $this->permanent_address,
            'permanent_cluster' => $this->permanent_cluster,
            'zipcode' => $this->zipcode,
            'country' => $this->country,
            'clsu_address' => $this->clsu_address,
            'clsu_cluster' => $this->clsu_cluster,
            'clsu_zipcode' => $this->clsu_zipcode,
            'clsu_country' => $this->clsu_country,
            
            // Family Background
            'father_fname' => $this->father_fname,
            'father_mname' => $this->father_mname,
            'father_lname' => $this->father_lname,
            'father_age' => $this->father_age,
            'father_contact' => $this->father_contact,
            'father_address' => $this->father_address,
            'father_education' => $this->father_education,
            'father_occupation' => $this->father_occupation,
            'mother_fname' => $this->mother_fname,
            'mother_mname' => $this->mother_mname,
            'mother_lname' => $this->mother_lname,
            'mother_age' => $this->mother_age,
            'mother_contact' => $this->mother_contact,
            'mother_address' => $this->mother_address,
            'mother_education' => $this->mother_education,
            'mother_occupation' => $this->mother_occupation,
            'guardian_name' => $this->guardian_name,
            'guardian_age' => $this->guardian_age,
            'guardian_contact' => $this->guardian_contact,
            'guardian_address' => $this->guardian_address,
            'guardian_occupation' => $this->guardian_occupation,
            'guardian_education' => $this->guardian_education,
            'guardian_relationship' => $this->guardian_relationship,
            'guardian_email' => $this->guardian_email,
            'emergency_person' => $this->emergency_person,
            'emergency_relationship' => $this->emergency_relationship,
            'emergency_contact' => $this->emergency_contact,
            'emergency_address' => $this->emergency_address,
            
            // Academic Background
            'elementary_school_address' => $this->elementary_school_address,
            'elementary_year' => $this->elementary_year,
            'elem_awards' => $this->elem_awards,
            'e_address' => $this->e_address,
            'high_school_address' => $this->high_school_address,
            'high_school_year' => $this->high_school_year,
            'high_school_awards' => $this->high_school_awards,
            'high_school_grad_year' => $this->high_school_grad_year,
            'high_school_average' => $this->high_school_average,
            'h_address' => $this->h_address,
            'senior_high_address' => $this->senior_high_address,
            'senior_high_cluster' => $this->senior_high_cluster,
            'senior_high_year' => $this->senior_high_year,
            'senior_high_school_awards' => $this->senior_high_school_awards,
            'sh_address' => $this->sh_address,
            'type_of_school' => $this->type_of_school,
            'strand' => $this->strand,
            
            // PWD/Disability
            'disability' => $this->disability,
            'disability_type' => $this->disability_type,
            'disability_proof' => $this->disability_proof,
            
            // Indigenous/Minority
            'indigenous' => $this->indigenous,
            'indigenous_type' => $this->indigenous_type,
            'indigenous_proof' => $this->indigenous_proof,
            
            // Family Income
            'family_income' => $this->family_income,
            'itr' => $this->itr,
            'four_p' => $this->four_p,
            'listahanan' => $this->listahanan,
            
            // Additional
            'no_brother' => $this->no_brother,
            'no_sister' => $this->no_sister,
            'first_generation' => $this->first_generation,
            'parent_marriage_status' => $this->parent_marriage_status,
            'working_student' => $this->working_student,
            'scholarship' => $this->scholarship,
            'is_updated' => $this->is_updated,
        ];
    }
}

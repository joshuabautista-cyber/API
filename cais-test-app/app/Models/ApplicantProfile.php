<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantProfile extends Model
{
    use HasFactory;

    protected $table = 'tbl_applicant_profile';
    protected $primaryKey = 'applicant_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'user_id',
        // Basic Information
        'lastname',
        'firstname',
        'middlename',
        'suffix',
        'age',
        'student_mobile_contact',
        'student_email',
        'course_program',
        
        // Personal Details
        'sex',
        'gender',
        'civil_status',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'religion_id',
        'citizenship_id',
        'birth_order',
        'sibling_in_college',
        'sibling_college_graduate',
        
        // Address Information
        'permanent_address',
        'permanent_cluster',
        'zipcode',
        'country',
        'clsu_address',
        'clsu_cluster',
        'clsu_zipcode',
        'clsu_country',
        
        // Family Background
        'father_fname',
        'father_mname',
        'father_lname',
        'father_age',
        'father_contact',
        'father_address',
        'father_education',
        'father_occupation',
        'mother_fname',
        'mother_mname',
        'mother_lname',
        'mother_age',
        'mother_contact',
        'mother_address',
        'mother_education',
        'mother_occupation',
        'guardian_name',
        'guardian_age',
        'guardian_contact',
        'guardian_address',
        'guardian_occupation',
        'guardian_education',
        'guardian_relationship',
        'guardian_email',
        'emergency_person',
        'emergency_relationship',
        'emergency_contact',
        'emergency_address',
        
        // Academic Background
        'elementary_school_address',
        'elementary_year',
        'elem_awards',
        'high_school_address',
        'high_school_year',
        'high_school_awards',
        'high_school_grad_year',
        'high_school_average',
        'senior_high_address',
        'senior_high_cluster',
        'senior_high_year',
        'senior_high_school_awards',
        'type_of_school',
        'strand',
        'vocational_school_address',
        'vocational_school_year',
        'vocational_awads',
        'college_school_address',
        'college_school_year',
        'college_awards',
        
        // PWD/Disability
        'disability',
        'disability_type',
        'disability_proof',
        
        // Indigenous/Minority
        'indigenous',
        'indigenous_type',
        'indigenous_proof',
        
        // Family Income
        'family_income',
        'itr',
        'four_p',
        'listahanan',
        
        // Additional fields
        'no_brother',
        'no_sister',
        'extra_curricular',
        'activities',
        'first_generation',
        'parent_marriage_status',
        'living_with_parent',
        'companions_at_home',
        'working_student',
        'study_habit',
        'study_habit_hours',
        'current_event',
        'reason_to_enroll',
        'personal_advocacy',
        'vision_health',
        'allergy',
        'medicine_take',
        'mental_health',
        'guidance_councilor',
        'visit_guidance_councilor',
        'guidance_councilor_assistance',
        'family_doctor',
        'family_doctor_contact',
        'scholarship',
        'program_id',
        'clsu_cat',
        'signatories',
        'is_updated',
        'e_address',
        'h_address',
        'sh_address',
    ];

    protected $casts = [
        'age' => 'integer',
        'father_age' => 'integer',
        'mother_age' => 'integer',
        'guardian_age' => 'integer',
        'no_brother' => 'integer',
        'no_sister' => 'integer',
        'high_school_average' => 'float',
        'clsu_cat' => 'float',
        'program_id' => 'integer',
        'is_updated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

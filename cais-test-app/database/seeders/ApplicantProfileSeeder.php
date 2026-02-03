<?php

namespace Database\Seeders;

use App\Models\ApplicantProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicantProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a test applicant profile linked to an existing user.
     */
    public function run(): void
    {
        // Create applicant profile for test user (user_id: 1033 based on the app's usage)
        // You can change the user_id to match your test user
        ApplicantProfile::create([
            'applicant_id' => 'APP-2022-1033',
            'user_id' => 1033,
            
            // Basic Information
            'firstname' => 'Juan',
            'middlename' => 'Dela',
            'lastname' => 'Cruz',
            'suffix' => '',
            'age' => 21,
            'student_mobile_contact' => '09123456789',
            'student_tel_contact' => '',
            'student_email' => 'juan.delacruz@example.com',
            'course_program' => '(BSIT) BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY',
            
            // Personal Details
            'sex' => 'Male',
            'gender' => 'Male',
            'civil_status' => 'Single',
            'date_of_birth' => '2003-05-15',
            'place_of_birth' => 'Science City of Muñoz, Nueva Ecija',
            'nationality' => 'Filipino',
            'religion_id' => 1,
            'citizenship_id' => 1,
            'birth_order' => '1st',
            'sibling_in_college' => 'No',
            'sibling_college_graduate' => 'No',
            
            // Address Information
            'permanent_address' => '123 Sample Street, Brgy. Rizal',
            'permanent_cluster' => 'Region III',
            'zipcode' => '3119',
            'country' => 'Philippines',
            'clsu_address' => 'CLSU Dormitory, Science City of Muñoz',
            'clsu_cluster' => 'Region III',
            'clsu_zipcode' => '3119',
            'clsu_country' => 'Philippines',
            
            // Family Background
            'father_fname' => 'Pedro',
            'father_mname' => 'Santos',
            'father_lname' => 'Cruz',
            'father_age' => 50,
            'father_contact' => '09111222333',
            'father_address' => '123 Sample Street, Brgy. Rizal',
            'father_education' => 'College Graduate',
            'father_occupation' => 'Engineer',
            'mother_fname' => 'Maria',
            'mother_mname' => 'Garcia',
            'mother_lname' => 'Cruz',
            'mother_age' => 48,
            'mother_contact' => '09222333444',
            'mother_address' => '123 Sample Street, Brgy. Rizal',
            'mother_education' => 'College Graduate',
            'mother_occupation' => 'Teacher',
            'guardian_name' => 'Maria G. Cruz',
            'guardian_age' => 48,
            'guardian_contact' => '09222333444',
            'guardian_address' => '123 Sample Street, Brgy. Rizal',
            'guardian_occupation' => 'Teacher',
            'guardian_education' => 'College Graduate',
            'guardian_relationship' => 'Mother',
            'guardian_email' => 'maria.cruz@example.com',
            'emergency_person' => 'Maria G. Cruz',
            'emergency_relationship' => 'Mother',
            'emergency_contact' => '09222333444',
            'emergency_address' => '123 Sample Street, Brgy. Rizal',
            
            // Academic Background
            'elementary_school_address' => 'Muñoz Central Elementary School',
            'elementary_year' => '2015-2016',
            'elem_awards' => 'With Honors',
            'e_address' => 'Science City of Muñoz, Nueva Ecija',
            'high_school_address' => 'Muñoz National High School',
            'high_school_year' => '2019-2020',
            'high_school_awards' => 'With High Honors',
            'high_school_grad_year' => '2020',
            'high_school_average' => 90.5,
            'h_address' => 'Science City of Muñoz, Nueva Ecija',
            'senior_high_address' => 'CLSU Science High School',
            'senior_high_cluster' => 'STEM',
            'senior_high_year' => '2021-2022',
            'senior_high_school_awards' => 'With Highest Honors',
            'sh_address' => 'Science City of Muñoz, Nueva Ecija',
            'type_of_school' => 'Public',
            'strand' => 'STEM',
            
            // PWD/Disability
            'disability' => 0,
            'disability_type' => null,
            'disability_proof' => null,
            
            // Indigenous/Minority
            'indigenous' => 0,
            'indigenous_type' => null,
            'indigenous_proof' => null,
            
            // Family Income
            'family_income' => '100,001 - 250,000',
            'itr' => null,
            'four_p' => 0,
            'listahanan' => 0,
            
            // Additional
            'no_brother' => 1,
            'no_sister' => 2,
            'first_generation' => 0,
            'parent_marriage_status' => 'Married',
            'working_student' => 0,
            'scholarship' => null,
            'is_updated' => 0,
        ]);

        $this->command->info('Applicant profile created for user_id: 1033');
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'profile_id'    => $this->profile_id,
            'fullname'      => $this->fname . ' ' . $this->mname . ' ' . $this->lname, 
            'fname'         => $this->fname,
            'mname'         => $this->mname,
            'lname'         => $this->lname,
            'emails'        => [
                'contact'  => $this->contact_email,
                'personal' => $this->p_email,
            ],
            'contact_no'    => $this->contact,
            'address'       => $this->address,
            'birthdate'     => $this->birthdate,
            'sex'           => $this->sex,
            
            'college'       => $this->college ? $this->college->college_name : null,
            'department'    => $this->department ? $this->department->dept_name : null, 
            'course'        => $this->course ? $this->course->course_name : null,      
            'college_id'    => $this->college_id,
            'dept_id'       => $this->dept_id,
            'course_id'     => $this->course_id,
            
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'semester_name'   => strtoupper($this->semester_name),
            'semester_no'     => $this->semester_no,
            'semester_year'   => strtoupper($this->semester_year),
            'semester_status' => $this->semester_status,
            'grades_deadline' => $this->grades_deadline,
        ];
    }
}


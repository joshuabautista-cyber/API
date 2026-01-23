<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradesResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'semester_id'    => $this->semester_id,
            'grade_id'       => $this->grade_id,
            'subjects'   => $this->classSchedule ? $this->classSchedule->subject_code : null,
            'units'          => $this->units,
            'grades'         => $this->grades,
            'remarks'        => $this->remarks,
        ];
    }
}
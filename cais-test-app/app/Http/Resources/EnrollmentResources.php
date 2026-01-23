<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'enrollment_id' => $this->enrollment_id,
            'user_id'      => $this->user_id,
            'semester_id'  => $this->semester_id,
            'course_id'     => $this->course_id,
            'section'      => strtoupper($this->section),
            'registration_only_tag' => (bool) $this->registration_only_tag,
        ];
    }
}

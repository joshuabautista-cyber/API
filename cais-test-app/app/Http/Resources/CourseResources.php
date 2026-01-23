<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "course_id"=> $this->course_id,
            'course_name' => strtoupper($this->course_name),
            'course_desc' => strtoupper($this->course_desc),
            'course_type' => strtoupper($this->course_type),
            'course_status' => strtoupper($this->course_status),
            'college'   => $this->whenLoaded('college', function () {
                return [
                    'college_id'   => $this->college->college_id,
                ];
            }),
        ];
    }
}

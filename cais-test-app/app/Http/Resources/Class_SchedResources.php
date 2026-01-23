<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Class_SchedResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'schedId' => $this->schedId, //
            'semester_id'   => $this->semester_id,
            'subject_code'  => strtoupper($this->subject_code),
            'cat_no'        => strtoupper($this->cat_no),
            'subject_title' => strtoupper($this->subject_title),
            'units'         => $this->units,
            'date'          => $this->date,
            'time'          => $this->time,
            'room'          => strtoupper($this->room),
            'section'       => strtoupper($this->section),

            'department' => $this->whenLoaded('department', function () {
                return [
                    'dept_id'   => $this->department->dept_id,
                ];
            }),

            'semester' => $this->whenLoaded('semester', function () {
                return [
                    'semester_id' => $this->semester->semester_id,
                ];
            }),

            'course' => $this->whenLoaded('course', function () {
                return [
                    'course_id' => $this->course->course_id,
                ];
            }),
        ];
    }
}
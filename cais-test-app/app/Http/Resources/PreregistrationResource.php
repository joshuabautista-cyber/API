<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreregistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'prereg_id' => $this->prereg_id,
            'user_id' => $this->user_id,
            'semester_id' => $this->semester_id,
            'course_id' => $this->course_id,
            'schedId' => $this->schedId,
            'section' => $this->section,
            'subject_code' => $this->subject_code,
            'subject_title' => $this->subject_title,
            'units' => $this->units,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            
            // Include related data when loaded
            'user' => $this->whenLoaded('user', function () {
                return [
                    'user_id' => $this->user->user_id,
                    'name' => $this->user->name ?? null,
                ];
            }),
            'semester' => $this->whenLoaded('semester', function () {
                return [
                    'semester_id' => $this->semester->semester_id,
                    'semester_name' => $this->semester->semester_name ?? null,
                ];
            }),
            'course' => $this->whenLoaded('course', function () {
                return [
                    'course_id' => $this->course->course_id,
                    'course_name' => $this->course->course_name ?? null,
                ];
            }),
        ];
    }
}

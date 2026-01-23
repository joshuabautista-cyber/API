<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'registration_id' => $this->registration_id,
            'user_id' => $this->user_id,
            'schedId' => $this->schedId,
            'enrollment_id' => $this->enrollment_id,
            'enrollment_type' => $this->enrollment_type,
            'ra_status' => $this->ra_status,
            'status' => $this->status,
            'ra_date_approved' => $this->ra_date_approved,
            'forced_drop' => $this->forced_drop,
            
            'user' => $this->whenLoaded('user'),
            'class_schedule' => $this->whenLoaded('classSchedule'),
            'enrollment' => $this->whenLoaded('enrollment'),
        ];
    }
}
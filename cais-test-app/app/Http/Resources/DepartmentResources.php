<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'dept_name' => strtoupper($this->dept_name),
            'dept_desc' => strtoupper($this->dept_desc),
            'college'   => $this->whenLoaded('college', function () {
                return [
                    'college_id'   => $this->college->college_id,
                    'college_name' => $this->college->college_name, // Ensure this matches your DB column name
                ];
            }),
        ];
    }
}

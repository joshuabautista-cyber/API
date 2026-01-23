<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollegeResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "college_id"=> $this->college_id,
            'college_name' => strtoupper($this->college_name),
            'college_desc' => strtoupper($this->college_desc),
        ];
    }
}

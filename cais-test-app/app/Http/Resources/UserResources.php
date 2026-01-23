<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'    => $this->user_id,
            'username'   => $this->uname,     
            'email'      => $this->email,
            'status'     => $this->status,
            'user_type'  => $this->usertype, 
            'last_login' => $this->last_login,
            'profile'    => new ProfileResources($this->whenLoaded('profile')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
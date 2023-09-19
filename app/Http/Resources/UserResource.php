<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "first_name"=>  $this->first_name,
            "last_name" => $this->last_name,
            'phone' => $this->phone,
            "username"=>  $this->username,
            "role" => $this->role->role_name,
            "role_id" => $this->role->id,
            "device_token" => $this->device_token,

        ];
    }
}

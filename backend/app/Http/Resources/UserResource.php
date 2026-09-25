<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'avatar'     => $this->avatar,
            'status'     => $this->status,
            'created_at' => $this->created_at?->toISOString(),

            'company' => $this->whenLoaded('company', fn () => [
                'id'   => $this->company->id,
                'name' => $this->company->name,
            ]),

            'roles' => $this->whenLoaded('roles', fn () =>
                $this->roles->map(fn ($role) => [
                    'id'   => $role->id,
                    'name' => $role->name,
                    'slug' => $role->slug,
                ])
            ),

            'permissions' => $this->getAllPermissions(),
        ];
    }
}
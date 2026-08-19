<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lead_code' => $this->lead_code,
            'customer_name' => $this->customer_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'source' => $this->source->value,
            'assigned_to' => $this->assigned_to,
            'assigned_user' => $this->whenLoaded(
                'assignedUser',
                fn () => [
                    'id' => $this->assignedUser->id,
                    'name' => $this->assignedUser->name,
                    'email' => $this->assignedUser->email,
                ]
            ),
            'status' => $this->status->value,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopSessionReservationResource
extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'enrollment' =>
            new WorkshopOfferingEnrollmentResource(
                $this->whenLoaded('enrollment')
            ),

            'session' =>
            new WorkshopSessionResource(
                $this->whenLoaded('session')
            ),

            /*
            |--------------------------------------------------------------------------
            | Reservation
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            'attended' => $this->attended,

            'is_waitlisted' =>
            $this->is_waitlisted,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'reserved_at' =>
            $this->reserved_at,

            'attended_at' =>
            $this->attended_at,

            'cancelled_at' =>
            $this->cancelled_at,

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            'notes' => $this->notes,

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

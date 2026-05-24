<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopAttendanceResource
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

            'reservation' =>
            new WorkshopSessionReservationResource(
                $this->whenLoaded('reservation')
            ),

            'marker' =>
            $this->whenLoaded('marker'),

            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            'checked_in_at' =>
            $this->checked_in_at,

            'checked_out_at' =>
            $this->checked_out_at,

            'attendance_minutes' =>
            $this->attendance_minutes,

            'remarks' =>
            $this->remarks,

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

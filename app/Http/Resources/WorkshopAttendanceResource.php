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

            'reservation_id' =>
            $this->reservation?->id,

            'student' => [

                'id' =>

                $this->reservation
                    ?->enrollment
                    ?->student
                    ?->id,

                'name' =>

                $this->reservation
                    ?->enrollment
                    ?->student
                    ?->name,

                'email' =>

                $this->reservation
                    ?->enrollment
                    ?->student
                    ?->email,
            ],

            'workshop' => [

                'id' =>
                $this->reservation
                    ?->enrollment
                    ?->offering
                    ?->workshop
                    ?->id,

                'title' =>
                $this->reservation
                    ?->enrollment
                    ?->offering
                    ?->workshop
                    ?->title,
            ],

            'offering' => [

                'id' =>

                $this->reservation
                    ?->enrollment
                    ?->offering
                    ?->id,

                'title' =>

                $this->reservation
                    ?->enrollment
                    ?->offering
                    ?->title,
            ],

            'session' => [

                'id' =>

                $this->reservation
                    ?->session
                    ?->id,

                'title' =>

                $this->reservation
                    ?->session
                    ?->title,

                'start_at' =>

                $this->reservation
                    ?->session
                    ?->start_at,
            ],

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

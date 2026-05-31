<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'session_number' => $this->session_number,

            'title' => $this->title,

            'session_kind' => $this->session_kind,

            'delivery_mode' => $this->delivery_mode,

            /*
            |--------------------------------------------------------------------------
            | Trainers
            |--------------------------------------------------------------------------
            */

            'trainer' => $this->whenLoaded('trainer'),

            'assistant_trainer' => $this->whenLoaded(
                'assistantTrainer'
            ),

            'offering' => $this->whenLoaded(
                'offering',
                function () {

                    return [

                        'id' =>
                        $this->offering->id,

                        'title' =>
                        $this->offering->title,
                    ];
                }
            ),

            'workshop' =>

            $this->when(

                $this->relationLoaded('offering')
                    && $this->offering?->relationLoaded('workshop'),

                function () {

                    return [

                        'id' =>
                        $this->offering->workshop->id,

                        'title' =>
                        $this->offering->workshop->title,
                    ];
                }
            ),

            'reservation_count' =>
            $this->whenCounted(
                'reservations'
            ),

            /*
            |--------------------------------------------------------------------------
            | Timing
            |--------------------------------------------------------------------------
            */

            'start_at' => $this->start_at,

            'end_at' => $this->end_at,

            'timezone' => $this->timezone,

            'duration_minutes' => $this->duration_minutes,

            /*
            |--------------------------------------------------------------------------
            | Venue
            |--------------------------------------------------------------------------
            */

            'venue_name' => $this->venue_name,

            'venue_address' => $this->venue_address,

            'meeting_link' => $this->meeting_link,

            /*
            |--------------------------------------------------------------------------
            | Learning
            |--------------------------------------------------------------------------
            */

            'agenda_summary' => $this->agenda_summary,

            'materials_required' => $this->materials_required,

            'prework' => $this->prework,

            'homework' => $this->homework,

            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            'capacity' => $this->capacity,

            'waitlist_enabled' => $this->waitlist_enabled,

            'bookable' => $this->bookable,

            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            'attendance_required' => $this->attendance_required,

            'completion_weight' => $this->completion_weight,

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            'recording_url' => $this->recording_url,

            'slides_url' => $this->slides_url,

            'resources' => $this->resources_json,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            /*
            |--------------------------------------------------------------------------
            | Computed
            |--------------------------------------------------------------------------
            */

            'is_upcoming' => now()->lt($this->start_at),

            'is_live' =>
            now()->between(
                $this->start_at,
                $this->end_at
            ),

            'is_completed' =>
            now()->gt($this->end_at),

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

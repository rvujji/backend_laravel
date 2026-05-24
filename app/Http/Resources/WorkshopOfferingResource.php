<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopOfferingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'title' => $this->title,

            'slug' => $this->slug,

            /*
            |--------------------------------------------------------------------------
            | Workshop
            |--------------------------------------------------------------------------
            */

            'workshop' => $this->whenLoaded('workshop'),

            'owner' => $this->whenLoaded('owner'),

            /*
            |--------------------------------------------------------------------------
            | Delivery + Rules
            |--------------------------------------------------------------------------
            */

            'delivery_mode' => $this->delivery_mode,

            'enrollment_type' =>
            $this->enrollment_type,

            'session_selection_rule' =>
            $this->session_selection_rule,

            'completion_rule' =>
            $this->completion_rule,

            'capacity_mode' =>
            $this->capacity_mode,

            /*
            |--------------------------------------------------------------------------
            | Selection Rules
            |--------------------------------------------------------------------------
            */

            'minimum_sessions_required' =>
            $this->minimum_sessions_required,

            'maximum_sessions_selectable' =>
            $this->maximum_sessions_selectable,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'start_date' => $this->start_date,

            'end_date' => $this->end_date,

            'enrollment_open_at' =>
            $this->enrollment_open_at,

            'enrollment_close_at' =>
            $this->enrollment_close_at,

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'capacity' => $this->capacity,

            'price' => $this->price,

            /*
            |--------------------------------------------------------------------------
            | Venue
            |--------------------------------------------------------------------------
            */

            'timezone' => $this->timezone,

            'venue_name' => $this->venue_name,

            'venue_address' => $this->venue_address,

            'meeting_link' => $this->meeting_link,

            /*
            |--------------------------------------------------------------------------
            | Certificate
            |--------------------------------------------------------------------------
            */

            'certificate_enabled' =>
            $this->certificate_enabled,

            /*
            |--------------------------------------------------------------------------
            | Sessions
            |--------------------------------------------------------------------------
            */

            'sessions' =>
            WorkshopSessionResource::collection(
                $this->whenLoaded('sessions')
            ),

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            'notes' => $this->notes,

            /*
            |--------------------------------------------------------------------------
            | Computed
            |--------------------------------------------------------------------------
            */

            'is_upcoming' =>
            $this->start_date
                ? now()->lt($this->start_date)
                : false,

            'has_started' =>
            $this->start_date
                ? now()->gte($this->start_date)
                : false,

            'has_ended' =>
            $this->end_date
                ? now()->gt($this->end_date)
                : false,

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

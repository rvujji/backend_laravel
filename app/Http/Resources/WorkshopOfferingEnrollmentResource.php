<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopOfferingEnrollmentResource
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

            'offering' => new WorkshopOfferingResource(
                $this->whenLoaded('offering')
            ),

            'student' => $this->whenLoaded('student'),

            /*
            |--------------------------------------------------------------------------
            | Enrollment
            |--------------------------------------------------------------------------
            */

            'status' => $this->status,

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_status' =>
            $this->payment_status,

            'amount_paid' =>
            $this->amount_paid,

            /*
            |--------------------------------------------------------------------------
            | Completion
            |--------------------------------------------------------------------------
            */

            'completion_status' =>
            $this->completion_status,

            'progress_percentage' =>
            $this->progress_percentage,

            'certificate_issued' =>
            $this->certificate_issued,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'enrolled_at' =>
            $this->enrolled_at,

            'cancelled_at' =>
            $this->cancelled_at,

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            'notes' => $this->notes,

            'is_completed' =>
            $this->completion_status === 'completed',

            'certificate_eligible' =>
            $this->completion_status === 'completed',

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

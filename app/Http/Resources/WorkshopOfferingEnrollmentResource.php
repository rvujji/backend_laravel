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

            'certificate' =>
            $this->certificate
                ? [
                    'id' => $this->certificate->id,

                    'certificate_number' =>
                    $this->certificate->certificate_number,

                    'issued_at' =>
                    $this->certificate->issued_at,

                    'certificate_url' =>
                    $this->certificate->pdf_path
                        ? url(
                            'storage/' .
                                $this->certificate->pdf_path
                        )
                        : null,

                    'downloadable' =>
                    !empty($this->certificate->pdf_path),
                ]
                : null,
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

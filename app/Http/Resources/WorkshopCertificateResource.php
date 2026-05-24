<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkshopCertificateResource
extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'certificate_number' =>
            $this->certificate_number,

            'issued_at' =>
            $this->issued_at,

            'status' =>
            $this->status,

            'pdf_url' =>
            $this->pdf_path
                ? asset(
                    'storage/' .
                        $this->pdf_path
                )
                : null,

            /*
            |--------------------------------------------------------------------------
            | Enrollment
            |--------------------------------------------------------------------------
            */

            'enrollment' =>
            new WorkshopOfferingEnrollmentResource(
                $this->whenLoaded('enrollment')
            ),

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' =>
            $this->created_at,

            'updated_at' =>
            $this->updated_at,
        ];
    }
}

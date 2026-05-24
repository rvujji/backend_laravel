<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkshopOfferingEnrollmentRequest
extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'workshop_offering_id' => [
                'required',
                'exists:workshop_offerings,id',
            ],
        ];
    }
}

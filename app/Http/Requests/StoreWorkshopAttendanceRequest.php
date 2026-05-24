<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkshopAttendanceRequest
extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'status' => [
                'nullable',
                'string',
            ],

            'checked_in_at' => [
                'nullable',
                'date',
            ],

            'checked_out_at' => [
                'nullable',
                'date',
            ],

            'attendance_minutes' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}

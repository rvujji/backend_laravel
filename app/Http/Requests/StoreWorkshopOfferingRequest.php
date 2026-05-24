<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkshopOfferingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'workshop_id' => [
                'required',
                'exists:workshops,id',
            ],

            'owner_id' => [
                'nullable',
                'exists:users,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'delivery_mode' => [
                'required',
                'string',
            ],

            'enrollment_type' => [
                'required',
                'string',
            ],

            'session_selection_rule' => [
                'required',
                'string',
            ],

            'completion_rule' => [
                'required',
                'string',
            ],

            'capacity_mode' => [
                'required',
                'string',
            ],

            'minimum_sessions_required' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'maximum_sessions_selectable' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'enrollment_open_at' => [
                'nullable',
                'date',
            ],

            'enrollment_close_at' => [
                'nullable',
                'date',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'timezone' => [
                'nullable',
                'string',
            ],

            'venue_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'venue_address' => [
                'nullable',
                'string',
            ],

            'meeting_link' => [
                'nullable',
                'url',
            ],

            'meeting_password' => [
                'nullable',
                'string',
                'max:255',
            ],

            'certificate_enabled' => [
                'boolean',
            ],

            'status' => [
                'nullable',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}

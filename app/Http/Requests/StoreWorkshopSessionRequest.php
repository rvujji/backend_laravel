<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkshopSessionRequest extends FormRequest
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

            'session_number' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'session_kind' => [
                'required',
                'string',
            ],

            'delivery_mode' => [
                'required',
                'string',
            ],

            'trainer_id' => [
                'nullable',
                'exists:users,id',
            ],

            'assistant_trainer_id' => [
                'nullable',
                'exists:users,id',
            ],

            'start_at' => [
                'required',
                'date',
            ],

            'end_at' => [
                'required',
                'date',
                'after:start_at',
            ],

            'timezone' => [
                'nullable',
                'string',
            ],

            'duration_minutes' => [
                'nullable',
                'integer',
                'min:1',
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

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'waitlist_enabled' => [
                'boolean',
            ],

            'bookable' => [
                'boolean',
            ],

            'agenda_summary' => [
                'nullable',
                'string',
            ],

            'materials_required' => [
                'nullable',
                'string',
            ],

            'prework' => [
                'nullable',
                'string',
            ],

            'homework' => [
                'nullable',
                'string',
            ],

            'attendance_required' => [
                'boolean',
            ],

            'completion_weight' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'recording_url' => [
                'nullable',
                'url',
            ],

            'slides_url' => [
                'nullable',
                'url',
            ],

            'resources_json' => [
                'nullable',
                'array',
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

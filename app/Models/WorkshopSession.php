<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workshop_offering_id',

        'session_number',

        'title',

        'session_kind',

        'delivery_mode',

        'trainer_id',
        'assistant_trainer_id',

        'start_at',
        'end_at',

        'timezone',

        'duration_minutes',

        'venue_name',
        'venue_address',

        'meeting_link',
        'meeting_password',

        'capacity',

        'waitlist_enabled',
        'bookable',

        'agenda_summary',

        'materials_required',
        'prework',
        'homework',

        'attendance_required',
        'completion_weight',

        'recording_url',
        'slides_url',

        'resources_json',

        'status',

        'notes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',

        'waitlist_enabled' => 'boolean',
        'bookable' => 'boolean',

        'attendance_required' => 'boolean',

        'resources_json' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function offering()
    {
        return $this->belongsTo(
            WorkshopOffering::class,
            'workshop_offering_id'
        );
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function assistantTrainer()
    {
        return $this->belongsTo(
            User::class,
            'assistant_trainer_id'
        );
    }

    public function reservations()
    {
        return $this->hasMany(
            WorkshopSessionReservation::class
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopOffering extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workshop_id',
        'owner_id',

        'title',
        'slug',

        'delivery_mode',

        'enrollment_type',
        'session_selection_rule',
        'completion_rule',
        'capacity_mode',

        'minimum_sessions_required',
        'maximum_sessions_selectable',

        'start_date',
        'end_date',

        'enrollment_open_at',
        'enrollment_close_at',

        'capacity',
        'price',

        'timezone',

        'venue_name',
        'venue_address',

        'meeting_link',
        'meeting_password',

        'certificate_enabled',

        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',

        'enrollment_open_at' => 'datetime',
        'enrollment_close_at' => 'datetime',

        'certificate_enabled' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sessions()
    {
        return $this->hasMany(WorkshopSession::class, 'workshop_offering_id');
    }

    public function enrollments()
    {
        return $this->hasMany(
            WorkshopOfferingEnrollment::class
        );
    }
}

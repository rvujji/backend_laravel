<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopSessionReservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'workshop_offering_enrollment_id',

        'workshop_session_id',

        'status',

        'attended',

        'attended_at',

        'reserved_at',

        'cancelled_at',

        'is_waitlisted',

        'notes',
    ];

    protected $casts = [

        'attended' => 'boolean',

        'is_waitlisted' => 'boolean',

        'attended_at' => 'datetime',

        'reserved_at' => 'datetime',

        'cancelled_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function enrollment()
    {
        return $this->belongsTo(
            WorkshopOfferingEnrollment::class,
            'workshop_offering_enrollment_id'
        );
    }

    public function session()
    {
        return $this->belongsTo(
            WorkshopSession::class,
            'workshop_session_id'
        );
    }

    public function attendance()
    {
        return $this->hasOne(
            WorkshopAttendance::class,
            'workshop_session_reservation_id'
        );
    }
}

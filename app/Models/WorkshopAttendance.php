<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopAttendance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'workshop_session_reservation_id',

        'status',

        'checked_in_at',

        'checked_out_at',

        'attendance_minutes',

        'remarks',

        'marked_by',
    ];

    protected $casts = [

        'checked_in_at' => 'datetime',

        'checked_out_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function reservation()
    {
        return $this->belongsTo(
            WorkshopSessionReservation::class,
            'workshop_session_reservation_id'
        );
    }

    public function marker()
    {
        return $this->belongsTo(
            User::class,
            'marked_by'
        );
    }
}

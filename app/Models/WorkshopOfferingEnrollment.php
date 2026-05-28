<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopOfferingEnrollment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'workshop_offering_id',

        'student_id',

        'status',

        'payment_status',

        'amount_paid',

        'completion_status',

        'progress_percentage',

        'certificate_issued',

        'enrolled_at',

        'cancelled_at',

        'notes',
    ];

    protected $casts = [

        'amount_paid' => 'decimal:2',

        'progress_percentage' => 'decimal:2',

        'certificate_issued' => 'boolean',

        'enrolled_at' => 'datetime',

        'cancelled_at' => 'datetime',
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

    public function student()
    {
        return $this->belongsTo(
            User::class,
            'student_id'
        );
    }

    

    public function reservations()
    {
        return $this->hasMany(
            WorkshopSessionReservation::class,
            'workshop_offering_enrollment_id'
        );
    }

    public function certificate()
    {
        return $this->hasOne(
            WorkshopCertificate::class,
            'workshop_offering_enrollment_id'
        );
    }
}

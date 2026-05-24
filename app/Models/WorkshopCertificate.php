<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopCertificate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'workshop_offering_enrollment_id',

        'certificate_number',

        'verification_code',

        'issued_at',

        'status',

        'pdf_path',
    ];

    protected $casts = [

        'issued_at' => 'datetime',
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
}

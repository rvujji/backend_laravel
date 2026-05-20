<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopEnrollment extends Model
{
    protected $fillable = [

        'workshop_id',

        'student_id',

        'status',

        'enrolled_at',

        'cancelled_at',

        'completed_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(
            Workshop::class,
            'workshop_id'
        );
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'student_id'
        );
    }
}
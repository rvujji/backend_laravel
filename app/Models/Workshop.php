<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'category_id',
        'owner_id',

        'title',
        'slug',

        'short_description',
        'full_description',

        'status',

        'price',

        'is_featured',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            WorkshopCategory::class,
            'category_id'
        );
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'owner_id'
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            WorkshopEnrollment::class,
            'workshop_id'
        );
    }
}
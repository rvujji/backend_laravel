<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkshopCategory extends Model
{
    protected $fillable = [

        'name',
        'slug',
        'description',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function workshops(): HasMany
    {
        return $this->hasMany(Workshop::class);
    }
}
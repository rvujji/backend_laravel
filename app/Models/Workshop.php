<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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
        'thumbnail',
        'video_url',
        'status',
        'price',
        'is_featured',
    ];
    protected $appends = [
        'thumbnail_url',
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

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }

        return url(
            '/api/media/' . $this->thumbnail
        );
    }

    public function offerings()
    {
        return $this->hasMany(WorkshopOffering::class);
    }
}

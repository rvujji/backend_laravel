<?php

namespace App\Services;

use App\Models\Workshop;
use App\Models\WorkshopCategory;

class PublicWorkshopService
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC WORKSHOPS
    |--------------------------------------------------------------------------
    */

    public function workshops(
        array $filters = []
    ) {

        return Workshop::query()

            ->with([
                'category',
                'owner'
            ])

            /*
            |--------------------------------------------------------------------------
            | ONLY PUBLISHED
            |--------------------------------------------------------------------------
            */

            ->where('status', 'published')

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            ->when(

                !empty($filters['search']),

                function ($query) use ($filters) {

                    $query->where(function ($q) use ($filters) {

                        $q->where(
                            'title',
                            'like',
                            '%' . $filters['search'] . '%'
                        )

                        ->orWhere(
                            'short_description',
                            'like',
                            '%' . $filters['search'] . '%'
                        );
                    });
                }
            )

            /*
            |--------------------------------------------------------------------------
            | CATEGORY FILTER
            |--------------------------------------------------------------------------
            */

            ->when(

                !empty($filters['category']),

                function ($query) use ($filters) {

                    $query->whereHas(

                        'category',

                        function ($q) use ($filters) {

                            $q->where(
                                'slug',
                                $filters['category']
                            );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | FEATURED FILTER
            |--------------------------------------------------------------------------
            */

            ->when(

                !empty($filters['featured']),

                function ($query) {

                    $query->where(
                        'is_featured',
                        true
                    );
                }
            )

            ->latest()

            ->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | WORKSHOP DETAILS
    |--------------------------------------------------------------------------
    */

    public function workshopBySlug(
        string $slug
    ): Workshop {

        return Workshop::query()

            ->with([
                'category',
                'owner'
            ])

            ->where(
                'status',
                'published'
            )

            ->where(
                'slug',
                $slug
            )

            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        return WorkshopCategory::query()

            ->where(
                'is_active',
                true
            )

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | FEATURED WORKSHOPS
    |--------------------------------------------------------------------------
    */

    public function featuredWorkshops()
    {
        return Workshop::query()

            ->with([
                'category',
                'owner'
            ])

            ->where(
                'status',
                'published'
            )

            ->where(
                'is_featured',
                true
            )

            ->latest()

            ->limit(6)

            ->get();
    }
}
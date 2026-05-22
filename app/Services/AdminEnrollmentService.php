<?php

namespace App\Services;

use App\Models\WorkshopEnrollment;

class AdminEnrollmentService
{
    /*
    |--------------------------------------------------------------------------
    | ALL ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    public function list(
        array $filters = []
    ) {
        return WorkshopEnrollment::query()

            ->with([

                'student:id,name,email',

                'workshop:id,title'
            ])

            /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

            ->when(

                !empty($filters['search']),

                function ($query) use ($filters) {

                    $query->whereHas(

                        'student',

                        function ($q) use ($filters) {

                            $q->where(
                                'name',
                                'like',
                                '%' . $filters['search'] . '%'
                            )

                                ->orWhere(
                                    'email',
                                    'like',
                                    '%' . $filters['search'] . '%'
                                );
                        }
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

            ->when(

                !empty($filters['status']),

                function ($query) use ($filters) {

                    $query->where(
                        'status',
                        $filters['status']
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | WORKSHOP FILTER
        |--------------------------------------------------------------------------
        */

            ->when(

                !empty($filters['workshop_id']),

                function ($query) use ($filters) {

                    $query->where(
                        'workshop_id',
                        $filters['workshop_id']
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | STUDENT FILTER
        |--------------------------------------------------------------------------
        */

            ->when(

                !empty($filters['student_id']),

                function ($query) use ($filters) {

                    $query->where(
                        'student_id',
                        $filters['student_id']
                    );
                }
            )

            ->latest()

            ->paginate(
                $filters['per_page'] ?? 20
            );
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopOfferingEnrollment;

use App\Http\Resources\WorkshopOfferingEnrollmentResource;

use Illuminate\Http\Request;

class AdminOfferingEnrollmentController
extends Controller
{
    public function index(Request $request)
    {
        $query =
            WorkshopOfferingEnrollment::with([
                'student',
                'offering',
                'offering.workshop',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas(
                'student',
                function ($q) use ($search) {

                    $q->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        if ($request->filled('offering_id')) {

            $query->where(
                'workshop_offering_id',
                $request->offering_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $enrollments = $query
            ->latest()
            ->paginate(
                $request->integer(
                    'per_page',
                    15
                )
            );

        return ApiResponse::success(
            'Offering enrollments fetched successfully.',
            WorkshopOfferingEnrollmentResource::collection(
                $enrollments
            ),
        );
    }
}

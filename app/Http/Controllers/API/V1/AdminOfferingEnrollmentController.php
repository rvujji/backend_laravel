<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopOffering;
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
                'certificate',
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

        if ($request->filled('workshop_id')) {

            $query->whereHas(
                'offering',
                function ($q) use ($request) {

                    $q->where(
                        'workshop_id',
                        $request->workshop_id
                    );
                }
            );
        }

        if ($request->filled('student_id')) {

            $query->where(
                'student_id',
                $request->student_id
            );
        }

        if ($request->filled('completion_status')) {

            $query->where(
                'completion_status',
                $request->completion_status
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

        return ApiResponse::paginated(
            'Offering enrollments fetched successfully.',
            $enrollments,
            WorkshopOfferingEnrollmentResource::class
        );
    }

    public function filters()
    {
        return ApiResponse::success(

            'Enrollment filters fetched successfully.',

            [

                'workshops' =>

                Workshop::query()

                    ->select(
                        'id',
                        'title'
                    )

                    ->orderBy('title')

                    ->get(),

                'offerings' =>

                WorkshopOffering::query()

                    ->select(
                        'id',
                        'workshop_id',
                        'title'
                    )

                    ->orderBy('title')

                    ->get(),

                'students' =>

                User::query()

                    ->select(
                        'id',
                        'name'
                    )

                    ->orderBy('name')

                    ->get(),

                'completion_statuses' => [

                    'not_started',
                    'in_progress',
                    'completed',
                ],
            ]
        );
    }
}

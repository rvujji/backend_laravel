<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Support\Facades\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopOffering;
use App\Models\WorkshopOfferingEnrollment;

use App\Services\WorkshopOfferingEnrollmentService;

use App\Http\Resources\WorkshopOfferingEnrollmentResource;

class MyOfferingEnrollmentController
extends Controller
{
    public function __construct(
        protected WorkshopOfferingEnrollmentService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | My Enrollments
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $enrollments =
            WorkshopOfferingEnrollment::with([
                'offering',
                'offering.workshop',
            ])
            ->where('student_id', auth()->id())
            ->latest()
            ->paginate();

        return ApiResponse::success(
            'My enrollments fetched successfully.',
            WorkshopOfferingEnrollmentResource::collection(
                $enrollments
            ),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Enroll
    |--------------------------------------------------------------------------
    */

    public function enroll(
        WorkshopOffering $offering
    ) {

        $enrollment = $this->service->enroll(
            $offering,
            auth()->id()
        );

        $enrollment->load([
            'offering',
            'offering.workshop',
        ]);

        return ApiResponse::success(
            'Enrollment successful.',
            new WorkshopOfferingEnrollmentResource(
                $enrollment
            ),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Enrollment
    |--------------------------------------------------------------------------
    */

    public function destroy(
        WorkshopOfferingEnrollment $enrollment
    ) {

        abort_if(
            $enrollment->student_id !== auth()->id(),
            403
        );

        $enrollment = $this->service->cancel($enrollment);

        return ApiResponse::success(
            'Enrollment cancelled successfully.',
            new WorkshopOfferingEnrollmentResource(
                $enrollment
            ),
        );
    }
}

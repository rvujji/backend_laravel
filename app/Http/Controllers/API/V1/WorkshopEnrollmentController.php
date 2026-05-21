<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreWorkshopEnrollmentRequest;

use App\Models\WorkshopEnrollment;

use App\Services\WorkshopEnrollmentService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkshopEnrollmentController extends Controller
{
    public function __construct(
        protected WorkshopEnrollmentService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | ENROLL
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreWorkshopEnrollmentRequest $request
    ): JsonResponse {

        $enrollment = $this->service->enroll(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Enrollment successful',
            $enrollment
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MY ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): JsonResponse {

        $enrollments = $this->service->myEnrollments(
            $request->user()
        );

        return ApiResponse::success(
            'Enrollments fetched successfully',
            $enrollments
        );
    }

    /*
|--------------------------------------------------------------------------
| MY ENROLLMENTS
|--------------------------------------------------------------------------
*/

    public function myEnrollments(
        Request $request
    ): JsonResponse {

        $enrollments = $this->service->myEnrollments(
            $request->user()
        );

        return ApiResponse::success(
            'My enrollments fetched successfully',
            $enrollments
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL ENROLLMENT
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        WorkshopEnrollment $workshopEnrollment
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | OWNERSHIP CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $workshopEnrollment->student_id
            !==
            $request->user()->id
        ) {

            return ApiResponse::error(
                'Unauthorized',
                null,
                403
            );
        }

        $enrollment = $this->service->cancel(
            $workshopEnrollment
        );

        return ApiResponse::success(
            'Enrollment cancelled successfully',
            $enrollment
        );
    }
}

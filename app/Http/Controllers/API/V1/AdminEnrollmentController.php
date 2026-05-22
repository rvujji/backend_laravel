<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\AdminEnrollmentService;

use Illuminate\Http\JsonResponse;

class AdminEnrollmentController extends Controller
{
    public function __construct(
        protected AdminEnrollmentService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | LIST ALL ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): JsonResponse {
        return ApiResponse::success(

            'Enrollments fetched successfully',

            $this->service->list(
                $request->all()
            )
        );
    }
}

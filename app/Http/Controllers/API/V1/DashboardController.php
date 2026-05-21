<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Services\DashboardService;

use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | STATS
    |--------------------------------------------------------------------------
    */

    public function stats(): JsonResponse
    {
        return ApiResponse::success(
            'Dashboard stats fetched successfully',
            $this->service->stats()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    public function recentEnrollments(): JsonResponse
    {
        return ApiResponse::success(
            'Recent enrollments fetched successfully',
            $this->service->recentEnrollments()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT WORKSHOPS
    |--------------------------------------------------------------------------
    */

    public function recentWorkshops(): JsonResponse
    {
        return ApiResponse::success(
            'Recent workshops fetched successfully',
            $this->service->recentWorkshops()
        );
    }
}

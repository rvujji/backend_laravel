<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Services\PublicWorkshopService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicWorkshopController extends Controller
{
    public function __construct(
        protected PublicWorkshopService $service
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC WORKSHOPS
    |--------------------------------------------------------------------------
    */

    public function workshops(
        Request $request
    ): JsonResponse {

        $workshops = $this->service->workshops(
            $request->all()
        );

        return ApiResponse::success(
            'Public workshops fetched successfully',
            $workshops
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WORKSHOP DETAILS
    |--------------------------------------------------------------------------
    */

    public function workshop(
        string $slug
    ): JsonResponse {

        $workshop = $this->service->workshopBySlug(
            $slug
        );

        return ApiResponse::success(
            'Workshop fetched successfully',
            $workshop
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    public function categories(): JsonResponse
    {
        $categories = $this->service->categories();

        return ApiResponse::success(
            'Categories fetched successfully',
            $categories
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FEATURED WORKSHOPS
    |--------------------------------------------------------------------------
    */

    public function featuredWorkshops(): JsonResponse
    {
        $workshops = $this->service->featuredWorkshops();

        return ApiResponse::success(
            'Featured workshops fetched successfully',
            $workshops
        );
    }
}
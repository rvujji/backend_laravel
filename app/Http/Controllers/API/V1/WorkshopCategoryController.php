<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreWorkshopCategoryRequest;
use App\Http\Requests\UpdateWorkshopCategoryRequest;

use App\Models\WorkshopCategory;

use App\Services\WorkshopCategoryService;

use Illuminate\Http\JsonResponse;

class WorkshopCategoryController extends Controller
{
    public function __construct(
        protected WorkshopCategoryService $service
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $categories = $this->service->list();

        return ApiResponse::success(
            'Workshop categories fetched successfully',
            $categories
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreWorkshopCategoryRequest $request
    ): JsonResponse {

        $category = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop category created successfully',
            $category
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateWorkshopCategoryRequest $request,
        WorkshopCategory $category
    ): JsonResponse {

        $category = $this->service->update(
            $category,
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop category updated successfully',
            $category
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        WorkshopCategory $category
    ): JsonResponse {

        $this->service->delete($category);

        return ApiResponse::success(
            'Workshop category deleted successfully'
        );
    }
}
<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;

use App\Models\Workshop;

use App\Services\WorkshopService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function __construct(
        protected WorkshopService $service
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $workshops = $this->service->list();

        return ApiResponse::success(
            'Workshops fetched successfully',
            $workshops
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreWorkshopRequest $request
    ): JsonResponse {

        $this->authorize('create', Workshop::class);

        $workshop = $this->service->create(
            $request->user(),
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop created successfully',
            $workshop
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateWorkshopRequest $request,
        Workshop $workshop
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | OWNERSHIP CHECK
        |--------------------------------------------------------------------------
        */
        $this->authorize(
            'update',
            $workshop
        );

        $workshop = $this->service->update(
            $workshop,
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop updated successfully',
            $workshop
        );

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        Workshop $workshop
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | OWNERSHIP CHECK
        |--------------------------------------------------------------------------
        */

         $this->authorize(
            'delete',
            $workshop
        );

        $this->service->delete($workshop);

        return ApiResponse::success(
            'Workshop deleted successfully'
        );
    }

    public function show(
        Workshop $workshop
    ): JsonResponse {

        return ApiResponse::success(
            'Workshop fetched successfully',
            $workshop->load([
                'category',
                'trainer'
            ])
        );
    }
}
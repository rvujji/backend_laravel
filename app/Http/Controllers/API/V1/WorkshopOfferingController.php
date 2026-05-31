<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopOffering;

use App\Services\WorkshopOfferingService;

use App\Http\Requests\StoreWorkshopOfferingRequest;
use App\Http\Requests\UpdateWorkshopOfferingRequest;

use App\Http\Resources\WorkshopOfferingResource;

class WorkshopOfferingController extends Controller
{
    public function __construct(
        protected WorkshopOfferingService $service
    ) {}

    public function index()
    {
        $offerings = WorkshopOffering::with([
            'workshop',
            'owner',
        ])
            ->latest()
            ->paginate();

        return ApiResponse::success(

            'Workshop offerings fetched successfully.',
            WorkshopOfferingResource::collection($offerings),
        );
    }

    public function store(
        StoreWorkshopOfferingRequest $request
    ) {

        $offering = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(

            'Workshop offering created successfully.',
            new WorkshopOfferingResource($offering),
        );
    }

    public function show(
        WorkshopOffering $offering
    ) {

        $offering->load([
            'workshop',
            'owner',
            'sessions',
        ]);

        return ApiResponse::success(
            'Workshop offering fetched successfully.',
            new WorkshopOfferingResource($offering),
        );
    }

    public function update(
        UpdateWorkshopOfferingRequest $request,
        WorkshopOffering $offering
    ) {

        $offering = $this->service->update(
            $offering,
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop offering updated successfully.',
            new WorkshopOfferingResource($offering),
        );
    }

    public function destroy(
        WorkshopOffering $offering
    ) {

        $offering = $this->service->delete($offering);

        return ApiResponse::success(
            'Workshop offering deleted successfully.',
            $offering,
        );
    }
}

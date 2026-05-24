<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopSession;

use App\Services\WorkshopSessionReservationService;

use App\Http\Requests\StoreWorkshopSessionRequest;
use App\Http\Requests\UpdateWorkshopSessionRequest;

use App\Http\Resources\WorkshopSessionResource;

class WorkshopSessionController extends Controller
{
    public function __construct(
        protected WorkshopSessionReservationService $service
    ) {}

    public function index()
    {
        $sessions = WorkshopSession::with([
            'offering',
            'trainer',
            'assistantTrainer',
        ])
            ->latest('start_at')
            ->paginate();

        return ApiResponse::success(

            'Workshop sessions fetched successfully.',
            WorkshopSessionResource::collection($sessions),
        );
    }

    public function store(
        StoreWorkshopSessionRequest $request
    ) {

        $session = $this->service->create(
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop session created successfully.',
            new WorkshopSessionResource($session),
        );
    }

    public function show(
        WorkshopSession $workshopSession
    ) {

        $workshopSession->load([
            'offering',
            'trainer',
            'assistantTrainer',
        ]);

        return ApiResponse::success(
            'Workshop session fetched successfully.',
            new WorkshopSessionResource($workshopSession),
        );
    }

    public function update(
        UpdateWorkshopSessionRequest $request,
        WorkshopSession $workshopSession
    ) {

        $session = $this->service->update(
            $workshopSession,
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop session updated successfully.',
            new WorkshopSessionResource($session),
        );
    }

    public function destroy(
        WorkshopSession $workshopSession
    ) {

        $session = $this->service->delete($workshopSession);

        return ApiResponse::success(
            'Workshop session deleted successfully.',
            $session
        );
    }
}

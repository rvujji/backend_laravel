<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopSession;

use App\Services\WorkshopSessionService;

use App\Http\Requests\StoreWorkshopSessionRequest;
use App\Http\Requests\UpdateWorkshopSessionRequest;

use App\Http\Resources\WorkshopSessionResource;
use Illuminate\Http\Request;

class WorkshopSessionController extends Controller
{
    public function __construct(
        protected WorkshopSessionService $service
    ) {}

    public function index(Request $request)
    {
        $query = WorkshopSession::query()

            ->with([
                'offering',
                'offering.workshop',
                'trainer',
                'assistantTrainer',
            ])
            ->withCount([
                'reservations',
            ]);

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {

            $query->where(
                'title',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Workshop Filter
    |--------------------------------------------------------------------------
    */

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

        /*
    |--------------------------------------------------------------------------
    | Offering Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('workshop_offering_id')) {

            $query->where(
                'workshop_offering_id',
                $request->workshop_offering_id
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $sessions =

            $query

            ->latest('start_at')

            ->paginate(
                $request->integer(
                    'per_page',
                    15
                )
            );

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
        WorkshopSession $session
    ) {

        $session->load([
            'offering',
            'trainer',
            'assistantTrainer',
        ]);

        return ApiResponse::success(
            'Workshop session fetched successfully.',
            new WorkshopSessionResource($session),
        );
    }

    public function update(
        UpdateWorkshopSessionRequest $request,
        WorkshopSession $session
    ) {

        $session = $this->service->update(
            $session,
            $request->validated()
        );

        return ApiResponse::success(
            'Workshop session updated successfully.',
            new WorkshopSessionResource($session),
        );
    }

    public function destroy(
        WorkshopSession $session
    ) {

        $session = $this->service->delete($session);

        return ApiResponse::success(
            'Workshop session deleted successfully.',
            $session
        );
    }
}

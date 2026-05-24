<?php

namespace App\Http\Controllers\API\V1;

use Exception;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopAttendance;
use App\Models\WorkshopSessionReservation;

use App\Services\WorkshopAttendanceService;

use App\Http\Requests\StoreWorkshopAttendanceRequest;

use App\Http\Resources\WorkshopAttendanceResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkshopAttendanceController
extends Controller
{
    public function __construct(
        protected WorkshopAttendanceService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Admin Attendance Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = WorkshopAttendance::with([
            'reservation',
            'reservation.session',
            'reservation.enrollment.student',
            'marker',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('session_id')) {

            $query->whereHas(
                'reservation',
                function ($q) use ($request) {

                    $q->where(
                        'workshop_session_id',
                        $request->session_id
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->latest()
            ->paginate(
                $request->integer(
                    'per_page',
                    15
                )
            );

        return ApiResponse::success(

            'Attendances fetched successfully.',
            WorkshopAttendanceResource::collection(
                $attendances
            ),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Attendance
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreWorkshopAttendanceRequest $request,
        WorkshopSessionReservation $reservation
    ) {

        try {

            $attendance =
                $this->service->markAttendance(
                    $reservation,
                    [
                        ...$request->validated(),

                        'marked_by' => Auth::id(),
                    ]
                );

            $attendance->load([
                'reservation',
                'reservation.session',
                'reservation.enrollment.student',
                'marker',
            ]);

            return ApiResponse::success(

                'Attendance marked successfully.',
                new WorkshopAttendanceResource(
                    $attendance
                ),
            );
        } catch (Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                422
            );
        }
    }
}

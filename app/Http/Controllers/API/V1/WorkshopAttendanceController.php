<?php

namespace App\Http\Controllers\API\V1;

use Exception;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\Workshop;
use App\Models\WorkshopAttendance;
use App\Models\WorkshopOffering;
use App\Models\WorkshopSession;
use App\Models\WorkshopSessionReservation;

use App\Services\WorkshopAttendanceService;

use App\Http\Requests\StoreWorkshopAttendanceRequest;
use App\Http\Requests\UpdateWorkshopAttendanceRequest;

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
            'reservation.enrollment',
            'reservation.enrollment.student',
            'reservation.enrollment.offering',
            'reservation.enrollment.offering.workshop',
            'marker',
        ])
            /*
    |--------------------------------------------------------------------------
    | Active Reservation Only
    |--------------------------------------------------------------------------
    */

            ->whereHas(
                'reservation',
                function ($q) {

                    $q->where(
                        'status',
                        '!=',
                        'cancelled'
                    );
                }
            )

            /*
    |--------------------------------------------------------------------------
    | Active Enrollment Only
    |--------------------------------------------------------------------------
    */

            ->whereHas(
                'reservation.enrollment',
                function ($q) {

                    $q->where(
                        'status',
                        '!=',
                        'cancelled'
                    );
                }
            )

            /*
    |--------------------------------------------------------------------------
    | Active Session Only
    |--------------------------------------------------------------------------
    */

            ->whereHas(
                'reservation.session',
                function ($q) {

                    $q->where(
                        'status',
                        '!=',
                        'cancelled'
                    );
                }
            )

            /*
    |--------------------------------------------------------------------------
    | Active Offering Only
    |--------------------------------------------------------------------------
    */

            ->whereHas(
                'reservation.enrollment.offering',
                function ($q) {

                    $q->where(
                        'status',
                        '!=',
                        'cancelled'
                    );
                }
            )

            /*
    |--------------------------------------------------------------------------
    | Active Workshop Only
    |--------------------------------------------------------------------------
    */

            ->whereHas(
                'reservation.enrollment.offering.workshop',
                function ($q) {

                    $q->where(
                        'status',
                        'published'
                    );
                }
            );
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

        if ($request->filled('workshop_id')) {

            $query->whereHas(

                'reservation.enrollment.offering.workshop',

                function ($q) use ($request) {

                    $q->where(
                        'id',
                        $request->workshop_id
                    );
                }
            );
        }
        if ($request->filled('offering_id')) {

            $query->whereHas(

                'reservation.enrollment.offering',

                function ($q) use ($request) {

                    $q->where(
                        'id',
                        $request->offering_id
                    );
                }
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

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas(

                'reservation.enrollment.student',

                function ($q) use ($search) {

                    $q->where(

                        function ($sub) use ($search) {

                            $sub->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )

                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            );
        }
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

    public function update(
        UpdateWorkshopAttendanceRequest $request,
        WorkshopAttendance $attendance
    ) {

        try {

            $attendance =
                $this->service->updateAttendance(
                    $attendance,
                    $request->validated()
                );

            $attendance->load([

                'reservation',

                'reservation.session',

                'reservation.enrollment',

                'reservation.enrollment.student',

                'reservation.enrollment.offering',

                'marker',
            ]);

            return ApiResponse::success(

                'Attendance updated successfully.',

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

    public function filters()
    {
        return ApiResponse::success(

            'Attendance filters fetched successfully.',

            [

                'workshops' =>

                Workshop::query()

                    ->where(
                        'status',
                        'published'
                    )

                    ->select([
                        'id',
                        'title',
                    ])

                    ->orderBy('title')

                    ->get(),

                'offerings' =>

                WorkshopOffering::query()

                    ->where(
                        'status',
                        'published'
                    )

                    ->select([
                        'id',
                        'title',
                    ])

                    ->orderBy('title')

                    ->get(),

                'sessions' =>

                WorkshopSession::query()

                    ->where(
                        'status',
                        '!=',
                        'cancelled'
                    )

                    ->select([
                        'id',
                        'title',
                        'start_at',
                    ])

                    ->orderBy('start_at')

                    ->get(),
            ]
        );
    }
}

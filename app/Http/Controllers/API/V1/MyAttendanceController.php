<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopAttendance;

use App\Http\Resources\WorkshopAttendanceResource;

use Illuminate\Support\Facades\Auth;

class MyAttendanceController extends Controller
{
    public function index()
    {
        $attendances =
            WorkshopAttendance::with([
                'reservation',
                'reservation.session',
                'reservation.session.offering',
                'reservation.session.offering.workshop',
            ])
            ->whereHas(
                'reservation.enrollment',
                function ($q) {

                    $q->where(
                        'student_id',
                        Auth::id()
                    );
                }
            )
            ->latest()
            ->paginate();

        return ApiResponse::success(

            'My attendances fetched successfully.',
            WorkshopAttendanceResource::collection(
                $attendances
            ),
        );
    }
}

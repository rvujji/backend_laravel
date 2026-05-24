<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopSessionReservation;

use App\Http\Resources\WorkshopSessionReservationResource;

use Illuminate\Http\Request;

class AdminSessionReservationController
extends Controller
{
    public function index(Request $request)
    {
        $query =
            WorkshopSessionReservation::with([
                'enrollment.student',
                'session',
                'session.offering',
                'session.offering.workshop',
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

            $query->where(
                'workshop_session_id',
                $request->session_id
            );
        }

        if ($request->filled('waitlisted')) {

            $query->where(
                'is_waitlisted',
                $request->boolean('waitlisted')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $reservations =
            $query->latest()
            ->paginate(
                $request->integer(
                    'per_page',
                    15
                )
            );

        return ApiResponse::success(

            'Session reservations fetched successfully.',
            WorkshopSessionReservationResource::collection(
                $reservations
            ),
        );
    }
}

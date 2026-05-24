<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\WorkshopSession;
use App\Models\WorkshopSessionReservation;
use App\Models\WorkshopOfferingEnrollment;

use App\Services\WorkshopSessionReservationService;

use App\Http\Resources\WorkshopSessionReservationResource;

use Illuminate\Support\Facades\Auth;
use Exception;

class MySessionReservationController
extends Controller
{
    public function __construct(
        protected WorkshopSessionReservationService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | My Reservations
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $reservations =
            WorkshopSessionReservation::with([
                'session',
                'session.offering',
                'session.offering.workshop',
            ])
            ->whereHas(
                'enrollment',
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

            'Session reservations fetched successfully.',
            WorkshopSessionReservationResource::collection(
                $reservations
            ),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reserve Session
    |--------------------------------------------------------------------------
    */

    public function reserve(
        WorkshopSession $session
    ) {

        try {

            $enrollment =
                WorkshopOfferingEnrollment::where(
                    'workshop_offering_id',
                    $session->workshop_offering_id
                )
                ->where(
                    'student_id',
                    Auth::id()
                )
                ->firstOrFail();

            $reservation = $this->service->reserve(
                $enrollment,
                $session
            );

            $reservation->load([
                'session',
                'session.offering',
                'session.offering.workshop',
            ]);

            return ApiResponse::success(
                'Session reserved successfully.',
                new WorkshopSessionReservationResource(
                    $reservation
                ),
            );
        } catch (Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                422
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Reservation
    |--------------------------------------------------------------------------
    */

    public function destroy(
        WorkshopSessionReservation $reservation
    ) {

        abort_if(
            $reservation
                ->enrollment
                ->student_id !== Auth::id(),
            403
        );

        try {

            $reservation = $this->service->cancel(
                $reservation
            );

            return ApiResponse::success(
                'Reservation cancelled successfully.',
                new WorkshopSessionReservationResource(
                    $reservation
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

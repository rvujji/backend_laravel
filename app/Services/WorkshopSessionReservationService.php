<?php

namespace App\Services;

use Exception;

use App\Models\WorkshopSession;
use App\Models\WorkshopOfferingEnrollment;
use App\Models\WorkshopSessionReservation;

class WorkshopSessionReservationService
{
    /*
    |--------------------------------------------------------------------------
    | Reserve Session
    |--------------------------------------------------------------------------
    */

    public function reserve(
        WorkshopOfferingEnrollment $enrollment,
        WorkshopSession $session
    ): WorkshopSessionReservation {

        /*
        |--------------------------------------------------------------------------
        | Validate Enrollment
        |--------------------------------------------------------------------------
        */

        if ($enrollment->status !== 'active') {

            throw new Exception(
                'Enrollment is not active.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Session Belongs To Offering
        |--------------------------------------------------------------------------
        */

        if (
            $session->workshop_offering_id !==
            $enrollment->workshop_offering_id
        ) {

            throw new Exception(
                'Session does not belong to enrolled offering.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Session Status
        |--------------------------------------------------------------------------
        */

        if ($session->status !== 'scheduled') {

            throw new Exception(
                'Session is not available for reservation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Bookable
        |--------------------------------------------------------------------------
        */

        if (!$session->bookable) {

            throw new Exception(
                'Session is not bookable.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Reservation
        |--------------------------------------------------------------------------
        */

        $alreadyReserved =
            WorkshopSessionReservation::where(
                'workshop_offering_enrollment_id',
                $enrollment->id
            )
            ->where(
                'workshop_session_id',
                $session->id
            )
            ->exists();

        if ($alreadyReserved) {

            throw new Exception(
                'Session already reserved.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Capacity Validation
        |--------------------------------------------------------------------------
        */

        if ($session->capacity) {

            $currentReservations =
                WorkshopSessionReservation::where(
                    'workshop_session_id',
                    $session->id
                )
                ->whereIn('status', [
                    'reserved',
                    'attended',
                ])
                ->count();

            if (
                $currentReservations >=
                $session->capacity
            ) {

                throw new Exception(
                    'Session capacity reached.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Selection Rule Validation
        |--------------------------------------------------------------------------
        */

        $offering = $enrollment->offering;

        if (
            $offering->session_selection_rule ===
            'any_n_of_m'
        ) {

            $reservationCount =
                WorkshopSessionReservation::where(
                    'workshop_offering_enrollment_id',
                    $enrollment->id
                )
                ->whereIn('status', [
                    'reserved',
                    'attended',
                ])
                ->count();

            if (
                $offering->maximum_sessions_selectable &&
                $reservationCount >=
                $offering
                ->maximum_sessions_selectable
            ) {

                throw new Exception(
                    'Maximum session reservation limit reached.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Create Reservation
        |--------------------------------------------------------------------------
        */

        return WorkshopSessionReservation::create([

            'workshop_offering_enrollment_id'
            => $enrollment->id,

            'workshop_session_id'
            => $session->id,

            'status' => 'reserved',

            'reserved_at' => now(),

            'attended' => false,

            'is_waitlisted' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Reservation
    |--------------------------------------------------------------------------
    */

    public function cancel(
        WorkshopSessionReservation $reservation
    ): WorkshopSessionReservation {

        if (
            in_array(
                $reservation->status,
                ['cancelled', 'attended']
            )
        ) {

            throw new Exception(
                'Reservation cannot be cancelled.'
            );
        }

        $reservation->update([

            'status' => 'cancelled',

            'cancelled_at' => now(),
        ]);

        return $reservation->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Attendance
    |--------------------------------------------------------------------------
    */

    public function markAttendance(
        WorkshopSessionReservation $reservation
    ): WorkshopSessionReservation {

        if (
            $reservation->status !== 'reserved'
        ) {

            throw new Exception(
                'Only reserved sessions can be attended.'
            );
        }

        $reservation->update([

            'status' => 'attended',

            'attended' => true,

            'attended_at' => now(),
        ]);

        return $reservation->fresh();
    }
}

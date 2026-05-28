<?php

namespace App\Services;

use Exception;

use App\Models\WorkshopAttendance;
use App\Models\WorkshopSessionReservation;
use App\Services\WorkshopCompletionService;

class WorkshopAttendanceService
{
    public function __construct(
        protected WorkshopCompletionService
        $completionService
    ) {}

    public function markAttendance(
        WorkshopSessionReservation $reservation,
        array $data
    ): WorkshopAttendance {

        /*
        |--------------------------------------------------------------------------
        | Validate Reservation
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $reservation->status,
                ['reserved', 'attended']
            )
        ) {

            throw new Exception(
                'Reservation is not eligible for attendance.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Attendance
        |--------------------------------------------------------------------------
        */

        if ($reservation->attendance) {

            throw new Exception(
                'Attendance already marked.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Attendance
        |--------------------------------------------------------------------------
        */

        $attendance = WorkshopAttendance::create([

            'workshop_session_reservation_id'
            => $reservation->id,

            'status' =>
            $data['status'] ?? 'present',

            'checked_in_at' =>
            $data['checked_in_at'] ?? now(),

            'checked_out_at' =>
            $data['checked_out_at'] ?? null,

            'attendance_minutes' =>
            $data['attendance_minutes'] ?? null,

            'remarks' =>
            $data['remarks'] ?? null,

            'marked_by' =>
            $data['marked_by'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Sync Reservation
        |--------------------------------------------------------------------------
        */

        $reservation->update([

            'status' => 'attended',

            'attended' => true,

            'attended_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Recalculate Completion
        |--------------------------------------------------------------------------
        */

        $this->completionService->evaluate(
            $reservation->enrollment
        );

        return $attendance->fresh();
    }

    public function updateAttendance(
        WorkshopAttendance $attendance,
        array $data
    ): WorkshopAttendance {

        /*
    |--------------------------------------------------------------------------
    | Prevent Editing Locked Attendance
    |--------------------------------------------------------------------------
    */

        // Optional future rule:
        // if certificate already issued

        $attendance->update([

            'status' =>
            $data['status'],
        ]);

        /*
    |--------------------------------------------------------------------------
    | TODO
    |--------------------------------------------------------------------------
    |
    | Recalculate:
    | - progress
    | - completion
    | - certificate eligibility
    |
    */

        return $attendance->fresh();
    }
}

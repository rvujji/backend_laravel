<?php

namespace App\Services;

use App\Models\WorkshopAttendance;
use App\Models\WorkshopOfferingEnrollment;

class EnrollmentProgressService
{
    public function recalculate(
        WorkshopOfferingEnrollment $enrollment
    ): WorkshopOfferingEnrollment {

        /*
        |--------------------------------------------------------------------------
        | Total Sessions
        |--------------------------------------------------------------------------
        */
        // logger('RECALCULATE START');
        $totalSessions =

            $enrollment
            ->offering
            ->sessions()

            ->where(
                'status',
                '!=',
                'cancelled'
            )

            ->count();

        /*
        |--------------------------------------------------------------------------
        | Attended Sessions
        |--------------------------------------------------------------------------
        */
        // logger('STEP 1');
        $attendedSessions =

            WorkshopAttendance::query()

            ->whereIn(
                'status',
                [
                    'present',
                    'late',
                    'partial',
                ]
            )

            ->whereHas(

                'reservation',

                function ($q) use ($enrollment) {

                    $q->where(
                        'workshop_offering_enrollment_id',
                        $enrollment->id
                    );
                }
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Attendance Percentage
        |--------------------------------------------------------------------------
        */
        // logger('STEP 2');
        $attendancePercentage =
            $totalSessions > 0

            ? round(
                (
                    $attendedSessions
                    / $totalSessions
                ) * 100,
                2
            )

            : 0;

        /*
        |--------------------------------------------------------------------------
        | Completion Logic
        |--------------------------------------------------------------------------
        */
        // logger('STEP 3');
        $completed =
            $attendedSessions >= $totalSessions
            && $totalSessions > 0;

        /*
        |--------------------------------------------------------------------------
        | Update Enrollment
        |--------------------------------------------------------------------------
        */

        $enrollment->update([

            'total_sessions' =>
            $totalSessions,

            'attended_sessions' =>
            $attendedSessions,

            'attendance_percentage' =>
            $attendancePercentage,

            'progress_percentage' =>
            $attendancePercentage,

            'completion_status' =>

            $completed
                ? 'completed'
                : 'in_progress',

            'certificate_eligible' =>
            $completed,
        ]);

        // logger([
        //     'enrollment_id' => $enrollment->id,
        //     'total_sessions' => $totalSessions,
        //     'attended_sessions' => $attendedSessions,
        //     'progress_percentage' => $attendancePercentage,
        //     'completed' => $completed,
        // ]);

        // logger('RECALCULATE END');
        return $enrollment->fresh();
    }
}

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

        $attendedSessions =

            WorkshopAttendance::query()

                ->whereHas(

                    'reservation',

                    function ($q) use ($enrollment) {

                        $q->where(
                            'workshop_offering_enrollment_id',
                            $enrollment->id
                        );
                    }
                )

                ->whereIn(
                    'status',
                    [
                        'present',
                        'late',
                        'partial',
                    ]
                )

                ->count();

        /*
        |--------------------------------------------------------------------------
        | Attendance Percentage
        |--------------------------------------------------------------------------
        */

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

        return $enrollment->fresh();
    }
}
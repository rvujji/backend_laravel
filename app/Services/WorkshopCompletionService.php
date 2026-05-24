<?php

namespace App\Services;

use App\Models\WorkshopAttendance;
use App\Models\WorkshopSessionReservation;
use App\Models\WorkshopOfferingEnrollment;

class WorkshopCompletionService
{
    public function evaluate(
        WorkshopOfferingEnrollment $enrollment
    ): WorkshopOfferingEnrollment {

        $offering = $enrollment->offering;

        /*
        |--------------------------------------------------------------------------
        | Total Sessions
        |--------------------------------------------------------------------------
        */

        $totalSessions =
            $offering->sessions()
            ->where('attendance_required', true)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Attended Sessions
        |--------------------------------------------------------------------------
        */

        $attendedSessions =
            WorkshopAttendance::whereHas(
                'reservation',
                function ($q) use ($enrollment) {

                    $q->where(
                        'workshop_offering_enrollment_id',
                        $enrollment->id
                    );
                }
            )
            ->whereIn('status', [
                'present',
                'late',
                'partial',
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Progress Percentage
        |--------------------------------------------------------------------------
        */

        $progressPercentage =
            $totalSessions > 0
            ? round(
                ($attendedSessions / $totalSessions) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Completion Logic
        |--------------------------------------------------------------------------
        */

        $completionStatus = 'in_progress';

        switch ($offering->completion_rule) {

            /*
            |--------------------------------------------------------------------------
            | Attend All Required
            |--------------------------------------------------------------------------
            */

            case 'attend_all_required':

                if (
                    $attendedSessions >=
                    $totalSessions
                ) {

                    $completionStatus = 'completed';
                }

                break;

            /*
            |--------------------------------------------------------------------------
            | Attend N Sessions
            |--------------------------------------------------------------------------
            */

            case 'attend_n_sessions':

                if (
                    $offering
                    ->minimum_sessions_required &&
                    $attendedSessions >=
                    $offering
                    ->minimum_sessions_required
                ) {

                    $completionStatus = 'completed';
                }

                break;

            /*
            |--------------------------------------------------------------------------
            | Attendance Percentage
            |--------------------------------------------------------------------------
            */

            case 'attendance_percentage':

                /*
                |--------------------------------------------------------------------------
                | Example:
                | 75% threshold
                |--------------------------------------------------------------------------
                */

                if ($progressPercentage >= 75) {

                    $completionStatus = 'completed';
                }

                break;

            /*
            |--------------------------------------------------------------------------
            | Manual Completion
            |--------------------------------------------------------------------------
            */

            case 'manual_completion':

                /*
                |--------------------------------------------------------------------------
                | Do nothing automatically
                |--------------------------------------------------------------------------
                */

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Enrollment
        |--------------------------------------------------------------------------
        */

        $enrollment->update([

            'completion_status' =>
            $completionStatus,

            'progress_percentage' =>
            $progressPercentage,
        ]);

        return $enrollment->fresh();
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkshopEnrollment;

class WorkshopEnrollmentService
{
    /*
    |--------------------------------------------------------------------------
    | ENROLL
    |--------------------------------------------------------------------------
    */

    public function enroll(
        User $student,
        array $data
    ): WorkshopEnrollment {

        return WorkshopEnrollment::create([

            'workshop_id' => $data['workshop_id'],

            'student_id' => $student->id,

            'status' => 'confirmed',

            'enrolled_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LIST MY ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    public function myEnrollments(
        User $student
    ) {

        return WorkshopEnrollment::query()

            ->with([
                'workshop',
                'workshop.category'
            ])

            ->where(
                'student_id',
                $student->id
            )

            ->latest()

            ->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        WorkshopEnrollment $enrollment
    ): WorkshopEnrollment {

        $enrollment->update([

            'status' => 'cancelled',

            'cancelled_at' => now(),
        ]);

        return $enrollment->refresh();
    }
}
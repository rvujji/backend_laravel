<?php

namespace App\Services;

use App\Models\WorkshopOffering;
use App\Models\WorkshopOfferingEnrollment;

class WorkshopOfferingEnrollmentService
{
    public function enroll(
        WorkshopOffering $offering,
        int $studentId
    ): WorkshopOfferingEnrollment {

        return WorkshopOfferingEnrollment::firstOrCreate(
            [
                'workshop_offering_id' => $offering->id,
                'student_id' => $studentId,
            ],
            [
                'status' => 'active',

                'payment_status' => 'pending',

                'completion_status' => 'not_started',

                'progress_percentage' => 0,

                'certificate_issued' => false,

                'enrolled_at' => now(),
            ]
        );
    }

    public function cancel(
        WorkshopOfferingEnrollment $enrollment
    ): WorkshopOfferingEnrollment {

        $enrollment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return $enrollment->fresh();
    }
}

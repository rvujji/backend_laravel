<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkshopSession;
use App\Models\WorkshopSessionReservation;
use App\Models\WorkshopOfferingEnrollment;

class WorkshopSessionReservationSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments =
            WorkshopOfferingEnrollment::all();

        foreach ($enrollments as $enrollment) {

            $sessions =
                WorkshopSession::where(
                    'workshop_offering_id',
                    $enrollment->workshop_offering_id
                )->get();

            foreach ($sessions as $session) {

                WorkshopSessionReservation::create([

                    'workshop_offering_enrollment_id' =>
                    $enrollment->id,

                    'workshop_session_id' =>
                    $session->id,

                    'status' =>
                    'reserved',
                ]);
            }
        }
    }
}

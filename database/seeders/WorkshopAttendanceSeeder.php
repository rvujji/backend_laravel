<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkshopAttendance;
use App\Models\WorkshopSessionReservation;

class WorkshopAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $reservations =
            WorkshopSessionReservation::all();

        foreach ($reservations as $reservation) {

            WorkshopAttendance::create([

                'workshop_session_reservation_id' =>
                $reservation->id,

                'status' =>
                collect([
                    'present',
                    'present',
                    'present',
                    'late',
                    'absent',
                ])->random(),
            ]);
        }
    }
}

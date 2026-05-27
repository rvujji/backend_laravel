<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkshopOffering;
use App\Models\WorkshopSession;

class WorkshopSessionSeeder extends Seeder
{
    public function run(): void
    {
        $offerings =
            WorkshopOffering::all();

        foreach ($offerings as $offering) {

            WorkshopSession::create([

                'workshop_offering_id' =>
                $offering->id,

                'title' =>
                'Day 1 - Fundamentals',

                'session_kind' =>
                'instruction',

                'delivery_mode' =>
                $offering->delivery_mode,

                'start_at' =>
                now()->addDays(5),

                'end_at' =>
                now()->addDays(5)->addHours(8),

                'capacity' =>
                30,

                'bookable' =>
                true,

                'attendance_required' =>
                true,

                'status' =>
                'scheduled',
            ]);

            WorkshopSession::create([

                'workshop_offering_id' =>
                $offering->id,

                'title' =>
                'Lab Session',

                'session_kind' =>
                'lab',

                'delivery_mode' =>
                $offering->delivery_mode,

                'start_at' =>
                now()->addDays(6),

                'end_at' =>
                now()->addDays(6)->addHours(6),

                'capacity' =>
                20,

                'bookable' =>
                true,

                'attendance_required' =>
                true,

                'status' =>
                'scheduled',
            ]);

            WorkshopSession::create([

                'workshop_offering_id' =>
                $offering->id,

                'title' =>
                'Assessment',

                'session_kind' =>
                'assessment',

                'delivery_mode' =>
                $offering->delivery_mode,

                'start_at' =>
                now()->addDays(7),

                'end_at' =>
                now()->addDays(7)->addHours(3),

                'capacity' =>
                30,

                'bookable' =>
                true,

                'attendance_required' =>
                true,

                'status' =>
                'scheduled',
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\WorkshopOffering;
use App\Models\WorkshopOfferingEnrollment;

class WorkshopOfferingEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students =
            User::role('student')->get();

        $offerings =
            WorkshopOffering::all();

        foreach ($students as $student) {

            foreach ($offerings->take(2) as $offering) {

                WorkshopOfferingEnrollment::create([

                    'workshop_offering_id' =>
                    $offering->id,

                    'student_id' =>
                    $student->id,

                    'status' =>
                    'enrolled',

                    'completion_status' =>
                    'in_progress',
                ]);
            }
        }
    }
}

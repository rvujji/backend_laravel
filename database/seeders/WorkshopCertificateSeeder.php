<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\WorkshopCertificate;
use App\Models\WorkshopOfferingEnrollment;

class WorkshopCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments =
            WorkshopOfferingEnrollment::take(5)->get();

        foreach ($enrollments as $enrollment) {

            WorkshopCertificate::create([

                'workshop_offering_enrollment_id' =>
                $enrollment->id,

                'certificate_number' =>
                'CERT-' . strtoupper(Str::random(8)),

                'verification_code' =>
                strtoupper(Str::random(12)),

                'issued_at' =>
                now(),

                'status' =>
                'issued',
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DevResetSeeder::class,
            RolesAndPermissionsSeeder::class,
            WorkshopCategorySeeder::class,
            UsersSeeder::class,
            WorkshopSeeder::class,
            WorkshopOfferingSeeder::class,
            WorkshopSessionSeeder::class,
            WorkshopOfferingEnrollmentSeeder::class,
            WorkshopSessionReservationSeeder::class,
            WorkshopAttendanceSeeder::class,
            // WorkshopCertificateSeeder::class,
        ]);
    }
}

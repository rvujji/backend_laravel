<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DevResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Disable Foreign Key Checks
        |--------------------------------------------------------------------------
        */

        Schema::disableForeignKeyConstraints();

        /*
        |--------------------------------------------------------------------------
        | Truncate Transaction Tables
        |--------------------------------------------------------------------------
        */

        DB::table('workshop_certificates')->truncate();

        DB::table('workshop_attendances')->truncate();

        DB::table('workshop_session_reservations')->truncate();

        DB::table('workshop_offering_enrollments')->truncate();

        DB::table('workshop_sessions')->truncate();

        DB::table('workshop_offerings')->truncate();
        /*
        |--------------------------------------------------------------------------
        | Optional Master Data Reset
        |--------------------------------------------------------------------------
        */

        DB::table('workshops')->truncate();

        DB::table('workshop_categories')->truncate();

        /*
        |--------------------------------------------------------------------------
        | Optional Auth Reset
        |--------------------------------------------------------------------------
        */

        DB::table('personal_access_tokens')->truncate();

        /*
        |--------------------------------------------------------------------------
        | Optional User Reset
        |--------------------------------------------------------------------------
        */

        // Uncomment if needed

        DB::table('users')->truncate();

        /*
        |--------------------------------------------------------------------------
        | Enable Foreign Key Checks
        |--------------------------------------------------------------------------
        */

        Schema::enableForeignKeyConstraints();
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'workshop_certificates',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Relationships
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'workshop_offering_enrollment_id'
                );

                $table->foreign(
                    'workshop_offering_enrollment_id',
                    'wc_enrollment_fk'
                )
                    ->references('id')
                    ->on('workshop_offering_enrollments')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Certificate Identity
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'certificate_number'
                )->unique();

                $table->string(
                    'verification_code'
                )->unique();

                /*
                |--------------------------------------------------------------------------
                | Issue Info
                |--------------------------------------------------------------------------
                */

                $table->timestamp(
                    'issued_at'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                $table->string('status')
                    ->default('issued');

                /*
                |--------------------------------------------------------------------------
                | PDF
                |--------------------------------------------------------------------------
                */

                $table->string('pdf_path')
                    ->nullable();

                $table->timestamps();

                $table->softDeletes();

                /*
                |--------------------------------------------------------------------------
                | Constraints
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    [
                        'workshop_offering_enrollment_id'
                    ],
                    'wc_enrollment_unique'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'workshop_certificates'
        );
    }
};

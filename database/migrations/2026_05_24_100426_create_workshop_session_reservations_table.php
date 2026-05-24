<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'workshop_session_reservations',
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
                    'wsr_enrollment_fk'
                )
                    ->references('id')
                    ->on('workshop_offering_enrollments')
                    ->cascadeOnDelete();

                $table->unsignedBigInteger(
                    'workshop_session_id'
                );

                $table->foreign(
                    'workshop_session_id',
                    'wsr_session_fk'
                )
                    ->references('id')
                    ->on('workshop_sessions')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Reservation Lifecycle
                |--------------------------------------------------------------------------
                */

                $table->string('status')
                    ->default('reserved');

                /*
                |--------------------------------------------------------------------------
                | Attendance
                |--------------------------------------------------------------------------
                */

                $table->boolean('attended')
                    ->default(false);

                $table->timestamp('attended_at')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Reservation Timing
                |--------------------------------------------------------------------------
                */

                $table->timestamp('reserved_at')
                    ->nullable();

                $table->timestamp('cancelled_at')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Waitlist
                |--------------------------------------------------------------------------
                */

                $table->boolean('is_waitlisted')
                    ->default(false);

                /*
                |--------------------------------------------------------------------------
                | Notes
                |--------------------------------------------------------------------------
                */

                $table->text('notes')
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
                        'workshop_offering_enrollment_id',
                        'workshop_session_id',
                    ],
                    'wsr_unique_reservation'
                );

                /*
                |--------------------------------------------------------------------------
                | Indexes
                |--------------------------------------------------------------------------
                */

                $table->index('status');

                $table->index('attended');

                $table->index('is_waitlisted');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'workshop_session_reservations'
        );
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'workshop_attendances',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Relationships
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'workshop_session_reservation_id'
                )
                    ->constrained()
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Attendance
                |--------------------------------------------------------------------------
                */

                $table->string('status')
                    ->default('present');

                /*
                |--------------------------------------------------------------------------
                | Timing
                |--------------------------------------------------------------------------
                */

                $table->timestamp('checked_in_at')
                    ->nullable();

                $table->timestamp('checked_out_at')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Duration
                |--------------------------------------------------------------------------
                */

                $table->unsignedInteger(
                    'attendance_minutes'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Remarks
                |--------------------------------------------------------------------------
                */

                $table->text('remarks')
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $table->foreignId('marked_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();

                /*
                |--------------------------------------------------------------------------
                | Constraints
                |--------------------------------------------------------------------------
                */

                $table->unique(
                    'workshop_session_reservation_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Indexes
                |--------------------------------------------------------------------------
                */

                $table->index('status');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'workshop_attendances'
        );
    }
};

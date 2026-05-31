<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'workshop_offering_enrollments',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Relationships
                |--------------------------------------------------------------------------
                */

                $table->foreignId('workshop_offering_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('student_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Enrollment Lifecycle
                |--------------------------------------------------------------------------
                */

                $table->string('status')
                    ->default('pending');

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                $table->string('payment_status')
                    ->default('pending');

                $table->decimal('amount_paid', 10, 2)
                    ->default(0);

                /*
                |--------------------------------------------------------------------------
                | Completion
                |--------------------------------------------------------------------------
                */

                $table->string('completion_status')
                    ->default('not_started');

                $table->boolean(
                    'certificate_eligible'
                )
                    ->default(false);

                $table->decimal('progress_percentage', 5, 2)
                    ->default(0);

                $table->boolean('certificate_issued')
                    ->default(false);

                $table->integer(
                    'total_sessions'
                )->default(0);

                $table->integer(
                    'attended_sessions'
                )->default(0);

                $table->decimal(
                    'attendance_percentage',
                    5,
                    2
                )
                    ->default(0);
                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $table->timestamp('enrolled_at')
                    ->nullable();

                $table->timestamp('cancelled_at')
                    ->nullable();

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
                        'workshop_offering_id',
                        'student_id',
                    ],
                    'woe_offering_student_unique'
                );

                /*
                |--------------------------------------------------------------------------
                | Indexes
                |--------------------------------------------------------------------------
                */

                $table->index('student_id');
                $table->index('status');
                $table->index('payment_status');
                $table->index('completion_status');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'workshop_offering_enrollments'
        );
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workshop_enrollments', function (Blueprint $table) {
            $table->id();

        /*
        |--------------------------------------------------------------------------
        | RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $table->foreignId('workshop_id')
            ->constrained('workshops')
            ->cascadeOnDelete();

        $table->foreignId('student_id')
            ->constrained('users')
            ->cascadeOnDelete();

        /*
        |--------------------------------------------------------------------------
        | ENROLLMENT STATUS
        |--------------------------------------------------------------------------
        */

        $table->enum('status', [
            'pending',
            'confirmed',
            'cancelled',
            'completed'
        ])->default('pending');

        /*
        |--------------------------------------------------------------------------
        | TIMESTAMPS
        |--------------------------------------------------------------------------
        */

        $table->timestamp('enrolled_at')
            ->nullable();

        $table->timestamp('cancelled_at')
            ->nullable();

        $table->timestamp('completed_at')
            ->nullable();

        $table->timestamps();

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE ENROLLMENT
        |--------------------------------------------------------------------------
        */

        $table->unique([
            'workshop_id',
            'student_id'
        ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshop_enrollments');
    }
};

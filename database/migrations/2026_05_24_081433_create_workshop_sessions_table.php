<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workshop_offering_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Session Identity
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('session_number')
                ->nullable();

            $table->string('title');

            /*
            |--------------------------------------------------------------------------
            | Session Type
            |--------------------------------------------------------------------------
            */

            $table->string('session_kind')
                ->default('theory');

            $table->string('delivery_mode')
                ->default('physical');

            /*
            |--------------------------------------------------------------------------
            | Trainers
            |--------------------------------------------------------------------------
            */

            $table->foreignId('trainer_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('assistant_trainer_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Timing
            |--------------------------------------------------------------------------
            */

            $table->timestamp('start_at');
            $table->timestamp('end_at');

            $table->string('timezone')
                ->default('Asia/Kolkata');

            $table->unsignedInteger('duration_minutes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Venue / Virtual
            |--------------------------------------------------------------------------
            */

            $table->string('venue_name')
                ->nullable();

            $table->text('venue_address')
                ->nullable();

            $table->string('meeting_link')
                ->nullable();

            $table->string('meeting_password')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('capacity')
                ->nullable();

            $table->boolean('waitlist_enabled')
                ->default(false);

            $table->boolean('bookable')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | Learning
            |--------------------------------------------------------------------------
            */

            $table->text('agenda_summary')
                ->nullable();

            $table->text('materials_required')
                ->nullable();

            $table->text('prework')
                ->nullable();

            $table->text('homework')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            $table->boolean('attendance_required')
                ->default(true);

            $table->decimal('completion_weight', 5, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            $table->string('recording_url')
                ->nullable();

            $table->string('slides_url')
                ->nullable();

            $table->json('resources_json')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('scheduled');

            $table->text('notes')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('workshop_offering_id');
            $table->index('trainer_id');
            $table->index('status');
            $table->index('start_at');
            $table->index('end_at');
            $table->index('session_kind');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_sessions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_offerings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workshop_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            /*
            |--------------------------------------------------------------------------
            | Delivery + Enrollment Rules
            |--------------------------------------------------------------------------
            */

            $table->string('delivery_mode')->default('physical');

            $table->string('enrollment_type')
                ->default('full_series');

            $table->string('session_selection_rule')
                ->default('all_sessions');

            $table->string('completion_rule')
                ->default('attend_all_required');

            $table->string('capacity_mode')
                ->default('offering_only');

            /*
            |--------------------------------------------------------------------------
            | Session Selection Rules
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('minimum_sessions_required')
                ->nullable();

            $table->unsignedInteger('maximum_sessions_selectable')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Scheduling
            |--------------------------------------------------------------------------
            */

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamp('enrollment_open_at')
                ->nullable();

            $table->timestamp('enrollment_close_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Capacity + Pricing
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('capacity')
                ->nullable();

            $table->decimal('price', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Location / Virtual
            |--------------------------------------------------------------------------
            */

            $table->string('timezone')
                ->default('Asia/Kolkata');

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
            | Certificates
            |--------------------------------------------------------------------------
            */

            $table->boolean('certificate_enabled')
                ->default(false);

            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('draft');

            $table->text('notes')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('workshop_id');
            $table->index('owner_id');
            $table->index('status');
            $table->index('delivery_mode');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_offerings');
    }
};

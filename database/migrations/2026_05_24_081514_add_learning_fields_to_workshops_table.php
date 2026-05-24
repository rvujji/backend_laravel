<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workshops', function (Blueprint $table) {

            $table->string('delivery_mode_default')
                ->nullable()
                ->after('video_url');

            $table->string('difficulty_level')
                ->nullable()
                ->after('delivery_mode_default');

            $table->string('language')
                ->default('English')
                ->after('difficulty_level');

            $table->json('tags')
                ->nullable()
                ->after('language');

            $table->text('learning_outcomes')
                ->nullable()
                ->after('tags');

            $table->text('requirements')
                ->nullable()
                ->after('learning_outcomes');

            $table->unsignedInteger('duration_minutes')
                ->nullable()
                ->after('requirements');

            $table->string('timezone')
                ->default('Asia/Kolkata')
                ->after('duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_mode_default',
                'difficulty_level',
                'language',
                'tags',
                'learning_outcomes',
                'requirements',
                'duration_minutes',
                'timezone',
            ]);
        });
    }
};

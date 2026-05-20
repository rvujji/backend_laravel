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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
        /*
        |--------------------------------------------------------------------------
        | RELATIONSHIPS
        |--------------------------------------------------------------------------
        */
        $table->foreignId('category_id')
            ->constrained('workshop_categories')
            ->cascadeOnDelete();
        $table->foreignId('owner_id')
            ->constrained('users')
            ->cascadeOnDelete();
        /*
        |--------------------------------------------------------------------------
        | BASIC DETAILS
        |--------------------------------------------------------------------------
        */
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('short_description')
            ->nullable();
        $table->longText('full_description')
            ->nullable();
        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        $table->enum('status', [
            'draft',
            'published',
            'archived'
        ])->default('draft');
        /*
        |--------------------------------------------------------------------------
        | PRICING
        |--------------------------------------------------------------------------
        */
        $table->decimal('price', 10, 2)
            ->default(0);
        /*
        |--------------------------------------------------------------------------
        | FLAGS
        |--------------------------------------------------------------------------
        */
        $table->boolean('is_featured')
            ->default(false);
        /*
        |--------------------------------------------------------------------------
        | TIMESTAMPS
        |--------------------------------------------------------------------------
        */
        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};

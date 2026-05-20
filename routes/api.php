<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\WorkshopCategoryController;
use App\Http\Controllers\API\V1\WorkshopController;
use App\Http\Controllers\API\V1\WorkshopEnrollmentController;
use App\Http\Controllers\API\V1\PublicWorkshopController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth')->group(function () {

        Route::post('/register', [
            AuthController::class,
            'register'
        ]);

        Route::post('/login', [
            AuthController::class,
            'login'
        ]);

        Route::middleware('auth:sanctum')->group(function () {

            Route::post('/logout', [
                AuthController::class,
                'logout'
            ]);

            Route::get('/me', [
                AuthController::class,
                'me'
            ]);
        });
    });

    Route::prefix('public')->group(function () {

        Route::get(
            '/workshops',
            [PublicWorkshopController::class, 'workshops']
        );

        Route::get(
            '/workshops/{slug}',
            [PublicWorkshopController::class, 'workshop']
        );

        Route::get(
            '/categories',
            [PublicWorkshopController::class, 'categories']
        );

        Route::get(
            '/featured-workshops',
            [PublicWorkshopController::class, 'featuredWorkshops']
        );
    });

    /*
    |--------------------------------------------------------------------------
    | PROTECTED ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource(
            'categories',
            WorkshopCategoryController::class
        )->middleware('role:admin');
        Route::apiResource(
            'workshops',
            WorkshopController::class
        );
        Route::apiResource(
            'enrollments',
            WorkshopEnrollmentController::class
        )->only([
            'index',
            'store',
            'destroy'
        ]);
    });
});
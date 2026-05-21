<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\WorkshopCategoryController;
use App\Http\Controllers\API\V1\WorkshopController;
use App\Http\Controllers\API\V1\WorkshopEnrollmentController;
use App\Http\Controllers\API\V1\PublicWorkshopController;
use App\Http\Controllers\API\V1\DashboardController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class,            'register']);
        Route::post('/login', [AuthController::class,            'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class,                'logout']);

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
        Route::prefix('me')->group(function () {

            Route::get(
                '/enrollments',
                [
                    WorkshopEnrollmentController::class,
                    'myEnrollments'
                ]
            );
        });
        Route::apiResource(
            'enrollments',
            WorkshopEnrollmentController::class
        )->only([
            'store',
            'destroy'
        ]);

        Route::prefix('dashboard')
            ->middleware('role:admin|trainer')
            ->group(function () {
                Route::get(
                    '/stats',
                    [DashboardController::class, 'stats']
                );
                Route::get(
                    '/recent-enrollments',
                    [DashboardController::class, 'recentEnrollments']
                );
                Route::get(
                    '/recent-workshops',
                    [DashboardController::class, 'recentWorkshops']
                );
            });
    });
});

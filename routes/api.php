<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\WorkshopCategoryController;
use App\Http\Controllers\API\V1\WorkshopController;
use App\Http\Controllers\API\V1\PublicWorkshopController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\WorkshopOfferingController;
use App\Http\Controllers\API\V1\WorkshopSessionController;
use App\Http\Controllers\API\V1\PublicOfferingController;
use App\Http\Controllers\API\V1\MyOfferingEnrollmentController;
use App\Http\Controllers\API\V1\AdminOfferingEnrollmentController;
use App\Http\Controllers\API\V1\MySessionReservationController;
use App\Http\Controllers\API\V1\AdminSessionReservationController;
use App\Http\Controllers\API\V1\WorkshopAttendanceController;
use App\Http\Controllers\API\V1\MyAttendanceController;
use App\Http\Controllers\API\V1\WorkshopCertificateController;

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
        /*
        |--------------------------------------------------------------------------
        | Offerings
        |--------------------------------------------------------------------------
        */

        Route::get(
            'offerings',
            [PublicOfferingController::class, 'index']
        );

        Route::get(
            'offerings/{slug}',
            [PublicOfferingController::class, 'show']
        );

        Route::get(
            'workshops/{slug}/offerings',
            [PublicOfferingController::class, 'workshopOfferings']
        );

        Route::get(
            'certificates/{certificate}/download',
            [WorkshopCertificateController::class, 'download']
        );
    });
    /*
    |--------------------------------------------------------------------------
    | HEALTH CHECK
    |--------------------------------------------------------------------------
    */
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'time' => now(),
        ]);
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
        )->middleware('role:admin|trainer');
        Route::apiResource(
            'workshops',
            WorkshopController::class
        );
        Route::prefix('admin')

            ->middleware('role:admin|trainer')

            ->group(function () {

                Route::apiResource(
                    'offerings',
                    WorkshopOfferingController::class
                );

                Route::apiResource(
                    'sessions',
                    WorkshopSessionController::class
                );
                Route::get(
                    'offering-enrollments',
                    [AdminOfferingEnrollmentController::class, 'index']
                );

                Route::get(
                    'session-reservations',
                    [AdminSessionReservationController::class, 'index']
                );

                Route::get(
                    'attendances',
                    [WorkshopAttendanceController::class, 'index']
                );

                Route::post(
                    'reservations/{reservation}/attendance',
                    [WorkshopAttendanceController::class, 'store']
                );

                Route::patch(
                    'attendances/{attendance}',
                    [
                        WorkshopAttendanceController::class,
                        'update',
                    ]
                );

                Route::get(
                    'attendance-filters',
                    [
                        WorkshopAttendanceController::class,
                        'filters',
                    ]
                );

                Route::post(
                    'enrollments/{enrollment}/certificate',
                    [WorkshopCertificateController::class, 'issue']
                );
            });
        Route::prefix('me')->group(function () {


            Route::get(
                'offering-enrollments',
                [MyOfferingEnrollmentController::class, 'index']
            );

            Route::post(
                'offerings/{offering}/enroll',
                [MyOfferingEnrollmentController::class, 'enroll']
            );

            Route::delete(
                'offering-enrollments/{enrollment}',
                [MyOfferingEnrollmentController::class, 'destroy']
            );

            Route::get(
                'session-reservations',
                [MySessionReservationController::class, 'index']
            );

            Route::post(
                'sessions/{session}/reserve',
                [MySessionReservationController::class, 'reserve']
            );

            Route::delete(
                'session-reservations/{reservation}',
                [MySessionReservationController::class, 'destroy']
            );

            Route::get(
                'attendances',
                [MyAttendanceController::class, 'index']
            );

            Route::get(
                'certificates',
                [WorkshopCertificateController::class, 'myCertificates']
            );
        });

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

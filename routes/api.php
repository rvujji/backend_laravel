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

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

Route::get(
    '/media/workshops/{file}',
    function (string $file) {

        $path = storage_path(
            'app/public/workshops/' . $file
        );

        abort_unless(
            file_exists($path),
            404
        );

        return response()->file(
            $path,
            [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET',
                'Access-Control-Allow-Headers' => '*',
            ]
        );
    }
);

Route::options(
    '/media/workshops/{filename}',
    function () {

        return response(
            '',
            204,
            [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => '*',
            ]
        );
    }
);

Route::get(
    '/auth/email/verify/{id}/{hash}',
    function (
        Request $request,
        $id,
        $hash
    ) {

        if (! URL::hasValidSignature($request)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification link.',
            ], 403);
        }

        $user = User::findOrFail($id);

        if (
            ! hash_equals(
                sha1($user->email),
                $hash
            )
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification hash.',
            ], 403);
        }

        if (! $user->hasVerifiedEmail()) {

            $user->markEmailAsVerified();
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
        ]);
    }
)
    ->name('verification.verify');

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class,            'register']);
        Route::post('/login', [AuthController::class,            'login']);
        Route::post(
            '/forgot-password',
            [AuthController::class, 'forgotPassword']
        );

        Route::post(
            '/reset-password',
            [AuthController::class, 'resetPassword']
        );
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class,                'logout']);

            Route::get('/me', [
                AuthController::class,
                'me'
            ]);
            Route::get('/email/status', [
                AuthController::class,
                'emailVerificationStatus'
            ]);
            Route::post(
                '/email/resend',
                [AuthController::class, 'resendVerificationEmail']
            );
            Route::post(
                '/change-password',
                [AuthController::class, 'changePassword']
            );
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
                    'offering-enrollments/filters',
                    [
                        AdminOfferingEnrollmentController::class,
                        'filters',
                    ]
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

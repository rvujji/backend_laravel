<?php

namespace App\Http\Controllers\API\V1;

use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Helpers\ApiResponse;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use App\Models\WorkshopCertificate;
use App\Models\WorkshopOfferingEnrollment;

use App\Services\WorkshopCertificateService;

use App\Http\Resources\WorkshopCertificateResource;

class WorkshopCertificateController
extends Controller
{
    public function __construct(
        protected WorkshopCertificateService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Issue Certificate
    |--------------------------------------------------------------------------
    */

    public function issue(
        WorkshopOfferingEnrollment $enrollment
    ) {

        try {

            $enrollment->load([
                'student',
                'offering',
                'offering.workshop',
            ]);

            $certificate =
                $this->service->issue(
                    $enrollment
                );

            $certificate->load([
                'enrollment',
                'enrollment.student',
                'enrollment.offering',
                'enrollment.offering.workshop',
            ]);

            return ApiResponse::success(

                'Certificate issued successfully.',
                new WorkshopCertificateResource(
                    $certificate
                ),
            );
        } catch (Exception $e) {
            Log::error('Error issuing certificate', [
                'message' => $e->getMessage(),
                'enrollment_id' => $enrollment->id,
                'exception' => $e,
            ]);
            return ApiResponse::error(
                $e->getMessage(),
                422
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Download Certificate
    |--------------------------------------------------------------------------
    */

    public function download(
        WorkshopCertificate $certificate
    ) {

        if (
            !$certificate->pdf_path ||
            !Storage::disk('public')->exists(
                $certificate->pdf_path
            )
        ) {

            return ApiResponse::error(
                'Certificate PDF not found.',
                404
            );
        }

        return response()->download(
            storage_path(
                'app/public/' .
                    $certificate->pdf_path
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | My Certificates
    |--------------------------------------------------------------------------
    */

    public function myCertificates()
    {
        $certificates =
            WorkshopCertificate::with([
                'enrollment',
                'enrollment.offering',
                'enrollment.offering.workshop',
            ])
            ->whereHas(
                'enrollment',
                function ($q) {

                    $q->where(
                        'student_id',
                        Auth::id()
                    );
                }
            )
            ->latest()
            ->paginate();

        return ApiResponse::success(

            'Certificates fetched successfully.',
            WorkshopCertificateResource::collection(
                $certificates
            ),
        );
    }
}

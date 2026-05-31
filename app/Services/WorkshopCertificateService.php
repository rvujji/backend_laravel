<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\WorkshopCertificate;
use App\Models\WorkshopOfferingEnrollment;

class WorkshopCertificateService
{
    /*
    |--------------------------------------------------------------------------
    | Issue Certificate
    |--------------------------------------------------------------------------
    */

    public function issue(
        WorkshopOfferingEnrollment $enrollment
    ): WorkshopCertificate {

        try {

            logger()->info(
                'Certificate issuance started',
                [
                    'enrollment_id' => $enrollment->id,
                    'completion_status' => $enrollment->completion_status,
                    'certificate_eligible' => $enrollment->certificate_eligible ?? null,
                    'certificate_issued' => $enrollment->certificate_issued ?? null,
                ]
            );

            if (
                $enrollment->completion_status !==
                'completed'
            ) {

                throw new Exception(
                    'Enrollment is not completed.'
                );
            }

            if ($enrollment->certificate) {

                logger()->info(
                    'Certificate already exists',
                    [
                        'enrollment_id' => $enrollment->id,
                        'certificate_id' => $enrollment->certificate->id,
                    ]
                );

                return $enrollment->certificate;
            }

            $certificate =
                WorkshopCertificate::create([

                    'workshop_offering_enrollment_id'
                    => $enrollment->id,

                    'certificate_number' =>
                    $this->generateCertificateNumber(),

                    'verification_code' =>
                    Str::uuid(),

                    'issued_at' =>
                    now(),

                    'status' =>
                    'issued',
                ]);

            logger()->info(
                'Certificate record created',
                [
                    'certificate_id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                ]
            );

            $pdfPath = $this->generatePdf(
                $certificate,
                $enrollment
            );

            $certificate->update([
                'pdf_path' => $pdfPath,
            ]);

            $enrollment->update([
                'certificate_issued' => true,
            ]);

            logger()->info(
                'Certificate issuance completed',
                [
                    'certificate_id' => $certificate->id,
                    'pdf_path' => $pdfPath,
                ]
            );

            return $certificate->fresh();
        } catch (\Throwable $e) {

            logger()->error(
                'Certificate issuance failed',
                [
                    'enrollment_id' => $enrollment->id ?? null,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate PDF
    |--------------------------------------------------------------------------
    */

    protected function generatePdf(
        WorkshopCertificate $certificate,
        WorkshopOfferingEnrollment $enrollment
    ): string {

        try {

            $student = $enrollment->student;
            $offering = $enrollment->offering;
            $workshop = $offering->workshop;

            $logoPath =
                public_path(
                    'storage/images/logo.png'
                );

            logger()->info(
                'Generating certificate PDF',
                [
                    'certificate_id' => $certificate->id,
                    'view' => 'pdfs.workshop-certificate',
                ]
            );

            $pdf = Pdf::loadView(

                'pdfs.workshop-certificate',

                [
                    'certificate' => $certificate,

                    'student' =>
                    $enrollment->student,

                    'offering' =>
                    $enrollment->offering,

                    'workshop' =>
                    $enrollment
                        ->offering
                        ->workshop,

                    'logoPath' => $logoPath,
                ]
            );

            $fileName =
                'certificate-' .
                $certificate->certificate_number .
                '.pdf';

            $path =
                'certificates/' .
                $fileName;

            Storage::disk('public')->put(
                $path,
                $pdf->output()
            );

            logger()->info(
                'Certificate PDF saved',
                [
                    'path' => $path,
                ]
            );

            return $path;
        } catch (\Throwable $e) {

            logger()->error(
                'Certificate PDF generation failed',
                [
                    'certificate_id' => $certificate->id ?? null,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Certificate Number
    |--------------------------------------------------------------------------
    */

    protected function generateCertificateNumber(): string
    {
        return 'CERT-' .
            strtoupper(Str::random(10));
    }
}

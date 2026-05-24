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

        /*
        |--------------------------------------------------------------------------
        | Validate Completion
        |--------------------------------------------------------------------------
        */

        if (
            $enrollment->completion_status !==
            'completed'
        ) {

            throw new Exception(
                'Enrollment is not completed.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Issued
        |--------------------------------------------------------------------------
        */

        if ($enrollment->certificate) {

            return $enrollment->certificate;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Certificate
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdfPath = $this->generatePdf(
            $certificate,
            $enrollment
        );

        /*
        |--------------------------------------------------------------------------
        | Update Certificate
        |--------------------------------------------------------------------------
        */

        $certificate->update([
            'pdf_path' => $pdfPath,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Enrollment
        |--------------------------------------------------------------------------
        */

        $enrollment->update([
            'certificate_issued' => true,
        ]);

        return $certificate->fresh();
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

        $student =
            $enrollment->student;

        $offering =
            $enrollment->offering;

        $workshop =
            $offering->workshop;

        /*
        |--------------------------------------------------------------------------
        | Load PDF View
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'pdfs.workshop-certificate',
            [
                'certificate' => $certificate,
                'student' => $student,
                'offering' => $offering,
                'workshop' => $workshop,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | File Path
        |--------------------------------------------------------------------------
        */

        $fileName =
            'certificate-' .
            $certificate->certificate_number .
            '.pdf';

        $path =
            'certificates/' .
            $fileName;

        /*
        |--------------------------------------------------------------------------
        | Save PDF
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')->put(
            $path,
            $pdf->output()
        );

        return $path;
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

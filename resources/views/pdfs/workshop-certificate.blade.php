<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>Certificate</title>

    <style>
        @page {
            margin: 0;
        }

        body {

            margin: 0;
            padding: 0;

            font-family: DejaVu Sans, sans-serif;

            color: #1f2937;

            background: #ffffff;
        }

        .certificate {

            position: relative;

            width: 100%;
            height: 100%;

            padding: 55px;

            box-sizing: border-box;
        }

        /*
        |--------------------------------------------------------------------------
        | Borders
        |--------------------------------------------------------------------------
        */

        .outer-border {

            position: fixed;

            top: 18px;
            left: 18px;

            width: 95%;

            height: 92%;

            border: 3px solid #d4af37;

            box-sizing: border-box;
        }

        .inner-border {

            position: fixed;

            top: 32px;
            left: 32px;

            width: 91%;

            height: 88%;

            border: 1px solid #d4af37;

            box-sizing: border-box;
        }

        /*
        |--------------------------------------------------------------------------
        | Watermark Logo
        |--------------------------------------------------------------------------
        */

        .watermark-logo {

            position: fixed;

            width: 320px;

            left: 50%;
            top: 48%;

            transform: translate(-50%, -50%);

            opacity: 0.04;
        }

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        .header {

            text-align: center;
        }

        .logo {

            width: 75px;

            margin-bottom: 10px;
        }

        .organization {

            font-size: 22px;

            font-weight: bold;

            color: #1e3a8a;

            letter-spacing: 4px;
        }

        .tagline {

            margin-top: 6px;

            color: #6b7280;

            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */

        .title {

            margin-top: 30px;

            text-align: center;

            font-size: 28px;

            font-weight: bold;

            color: #0f172a;

            letter-spacing: 2px;
        }

        .title-divider {

            width: 260px;

            height: 2px;

            background: #d4af37;

            margin: 14px auto;
        }

        .subtitle {

            text-align: center;

            font-size: 12px;

            color: #6b7280;
        }

        /*
        |--------------------------------------------------------------------------
        | Recipient
        |--------------------------------------------------------------------------
        */

        .presented {

            margin-top: 25px;

            text-align: center;

            font-size: 14px;
        }

        .recipient {

            margin-top: 10px;

            text-align: center;

            font-size: 36px;

            font-weight: bold;

            color: #1e3a8a;
        }

        .achievement-container {

            width: 80%;

            margin: 0 auto;
        }

        .achievement {

            margin-top: 12px;

            text-align: center;

            font-size: 15px;

            line-height: 1.8;
        }

        .program {

            margin-top: 10px;

            text-align: center;

            font-size: 26px;

            font-weight: bold;

            color: #0f172a;
        }

        /*
        |--------------------------------------------------------------------------
        | Metadata
        |--------------------------------------------------------------------------
        */
        .details-wrapper {

            width: 100%;

            margin-top: 25px;
        }

        .details {

            width: 100%;

            border-collapse: collapse;

            margin: 0;
        }

        .details td {
            width: 50%;

            white-space: nowrap;

            padding: 12px;

            border-top: 1px solid #e5e7eb;
        }

        .label {

            font-weight: bold;

            color: #374151;
        }

        /*
        |--------------------------------------------------------------------------
        | Footer
        |--------------------------------------------------------------------------
        */

        .footer {

            margin-top: 25px;
            width: 100%;
        }

        .footer-table {

            width: 100%;
            table-layout: fixed;
        }

        .brand {

            font-size: 11px;

            color: #4b5563;

            line-height: 1.7;
        }

        .signature {

            text-align: center;
        }

        .signature-line {

            width: 180px;

            margin: 0 auto 10px auto;

            border-top: 1px solid #111827;
        }

        .signature-name {

            font-size: 13px;

            font-weight: bold;
        }

        .signature-role {

            font-size: 11px;

            color: #6b7280;
        }

        .seal {

            width: 80px;
            height: 80px;

            margin: 0 auto;

            border: 2px solid #d4af37;

            border-radius: 50%;

            text-align: center;

            color: #1e3a8a;

            font-size: 9px;

            font-weight: bold;

            padding-top: 20px;

            box-sizing: border-box;
        }
    </style>

</head>

<body>
    <div class="certificate">

        <div class="outer-border"></div>
        <div class="inner-border"></div>

        <img
            src="{{ $logoPath }}"
            class="watermark-logo">

        <div class="header">

            <img
                src="{{ $logoPath }}"
                class="logo">

            <div class="organization">
                PRAGNA SKILL GARAGE
            </div>

            <div class="tagline">
                Professional Learning • Training • Certification
            </div>

        </div>

        <div class="title">
            CERTIFICATE OF ACHIEVEMENT
        </div>

        <div class="title-divider"></div>

        <div class="subtitle">
            Awarded for successful completion of a learning program
        </div>

        <div class="presented">
            This certificate is proudly presented to
        </div>

        <div class="recipient">
            {{ $student->name }}
        </div>

        <div class="achievement">
            for successfully completing the learning program
        </div>

        <div class="program">
            {{ $workshop->title }}
        </div>

        <div class="achievement-container">

            <div class="achievement">

                conducted through

                <strong>
                    {{ $offering->title }}
                </strong>

                and demonstrating satisfactory participation
                and achievement.

            </div>

        </div>

        <div class="details-wrapper">
            <table class="details">

                <tr>

                    <td>

                        <span class="label">
                            Certificate No:
                        </span>

                        {{ $certificate->certificate_number }}

                    </td>

                    <td>

                        <span class="label">
                            Completion Date:
                        </span>

                        {{ \Carbon\Carbon::parse(
                    $certificate->issued_at
                )->format('d M Y') }}

                    </td>

                </tr>

                <tr>

                    <td>

                        <span class="label">
                            Issued By:
                        </span>

                        Pragna Skill Garage

                    </td>

                    <td>

                        <span class="label">
                            Credential Type:
                        </span>

                        Learning Program

                    </td>

                </tr>

            </table>
        </div>

        <div class="footer">

            <table class="footer-table">

                <tr>

                    <td width="33%">

                        <div class="brand">

                            Pragna Skill Garage

                            <br>

                            Empowering learners through
                            technology, engineering,
                            and professional development.

                        </div>

                    </td>

                    <td width="34%">

                        <div class="signature">

                            <div class="signature-line"></div>

                            <div class="signature-name">
                                Authorized Signatory
                            </div>

                            <div class="signature-role">
                                Pragna Skill Garage
                            </div>

                        </div>

                    </td>

                    <td width="33%">

                        <div class="seal">

                            CERTIFIED

                            <br>

                            PSG

                        </div>

                    </td>

                </tr>

            </table>

        </div>

    </div>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Workshop Certificate</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
            padding: 60px;
            border: 10px solid #333;
        }

        .title {
            font-size: 40px;
            margin-bottom: 40px;
        }

        .subtitle {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .name {
            font-size: 34px;
            font-weight: bold;
            margin: 30px 0;
        }

        .workshop {
            font-size: 28px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 60px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="title">
        Certificate of Completion
    </div>

    <div class="subtitle">
        This is to certify that
    </div>

    <div class="name">
        {{ $student->name }}
    </div>

    <div class="subtitle">
        has successfully completed the workshop
    </div>

    <div class="workshop">
        {{ $workshop->title }}
    </div>

    <div class="subtitle">
        Offering: {{ $offering->title }}
    </div>

    <div class="footer">

        Certificate Number:
        {{ $certificate->certificate_number }}

        <br><br>

        Issued On:
        {{ $certificate->issued_at->format('d M Y') }}

    </div>

</body>

</html>
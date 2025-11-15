<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f3f6fc;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 0;
            background: #f3f6fc;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .header {
            background: #3A66CC;
            color: #ffffff;
            padding: 25px 30px;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .body {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 12px 28px;
            margin: 20px 0;
            background: #3A66CC;
            border-radius: 50px;
            color: #ffffff !important;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
        }

        .note {
            font-size: 13px;
            color: #888888;
            margin-top: 25px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }

        .code-box {
            background: #eef3ff;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            border: 1px solid #d6e0ff;
            margin: 20px 0;
            color: #3A66CC;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">

            <!-- HEADER -->
            <div class="header">
                Reset Password
            </div>

            <!-- BODY -->
            <div class="body">
                <p>Halo,</p>

                <p>Kami menerima permintaan untuk mereset password akun Anda. Jika Anda tidak meminta reset ini, abaikan email ini.</p>

                <!-- BUTTON -->
                <p style="text-align:center;">
                    <a href="{{ $resetUrl }}" class="button">
                        Reset Password
                    </a>
                </p>

                <!-- OPTIONAL TOKEN (Jika ingin menampilkan token saja) -->
                @if(isset($token))
                <p>Kode reset password Anda:</p>
                <div class="code-box">{{ $token }}</div>
                @endif

                <p class="note">
                    Link reset password ini hanya berlaku selama 60 menit dan hanya dapat digunakan sekali.
                </p>

                <p>Terima kasih,<br>
                    <strong>{{ config('app.name') }}</strong>
                </p>
            </div>

            <!-- FOOTER -->
            <div class="footer">
                Email ini dikirim otomatis, mohon tidak membalas.
            </div>

        </div>
    </div>
</body>

</html>
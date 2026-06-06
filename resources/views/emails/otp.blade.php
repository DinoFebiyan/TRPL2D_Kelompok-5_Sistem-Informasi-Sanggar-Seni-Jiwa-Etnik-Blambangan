<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP</title>
    <style>
        body {
            font-family: 'Nunito', Arial, sans-serif;
            background-color: #FFF8F8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 560px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(192, 39, 45, 0.12);
        }
        .header {
            background-color: #C0272D;
            padding: 36px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0 0 4px 0;
            font-weight: 700;
        }
        .header p {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .body {
            padding: 40px;
        }
        .body p {
            color: #2C1A1A;
            font-size: 15px;
            line-height: 1.7;
            margin: 0 0 16px 0;
        }
        .otp-box {
            background: #FFF5F5;
            border: 2px dashed #C0272D;
            border-radius: 12px;
            text-align: center;
            padding: 24px;
            margin: 24px 0;
        }
        .otp-box .label {
            font-size: 12px;
            color: #9A8080;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            color: #C0272D;
            letter-spacing: 10px;
        }
        .otp-box .expire {
            font-size: 12px;
            color: #9A8080;
            margin-top: 8px;
        }
        .warning {
            background: #FFF8F0;
            border-left: 4px solid #dfb15b;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            font-size: 13px;
            color: #7a5c00;
            margin-top: 20px;
        }
        .footer {
            background: #F9F0F0;
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #9A8080;
        }
        .footer strong {
            color: #C0272D;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sanggar Tari <em style="color:#dfb15b;">JEB</em></h1>
            <p>Singojuruh – Banyuwangi</p>
        </div>

        <div class="body">
            <p>Halo, <strong>{{ $user->name }}</strong>!</p>
            <p>
                Kami menerima permintaan reset password untuk akun Anda.
                Gunakan kode verifikasi berikut untuk melanjutkan:
            </p>

            <div class="otp-box">
                <div class="label">Kode Verifikasi OTP</div>
                <div class="otp-code">{{ $kode }}</div>
                <div class="expire">⏱ Kode berlaku selama <strong>10 menit</strong></div>
            </div>

            <p>
                Masukkan kode di atas pada halaman verifikasi yang sudah terbuka di browser Anda.
            </p>

            <div class="warning">
                ⚠️ <strong>Jangan bagikan kode ini</strong> kepada siapapun, termasuk pihak yang mengaku dari Sanggar Tari JEB.
                Jika Anda tidak meminta reset password, abaikan email ini.
            </div>
        </div>

        <div class="footer">
            Email ini dikirim secara otomatis oleh sistem <strong>Sanggar Tari JEB</strong>.<br>
            Harap tidak membalas email ini.
        </div>
    </div>
</body>
</html>

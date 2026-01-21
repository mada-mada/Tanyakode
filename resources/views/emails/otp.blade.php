<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        .code { font-size: 32px; font-weight: bold; color: #007bff; letter-spacing: 5px; text-align: center; margin: 20px 0; background: #e9ecef; padding: 10px; border-radius: 5px; }
        .footer { font-size: 12px; color: #888; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Halo, {{ $user->username }}!</h2>
        <p>Terima kasih telah mendaftar. Untuk mengaktifkan akun Anda, silakan masukkan kode verifikasi berikut:</p>

        <div class="code">
            {{ $otpCode }}
        </div>

        <p>Kode ini hanya berlaku selama <strong>5 menit</strong>.</p>
        <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>

        <br>
        <p>Salam,<br>Tim IT Sekolah</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Sistem Informasi Sekolah. All rights reserved.
    </div>
</body>
</html>

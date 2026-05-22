<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun SmartMBG Anda Telah Aktif</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F0F9FF; margin: 0; padding: 40px 20px; color: #1F2937;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <div style="background-color: #3B82F6; padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; letter-spacing: 1px;">Smart<span style="color: #DBEAFE;">MBG</span></h1>
        </div>

        <div style="padding: 40px 30px;">
            <h2 style="margin-top: 0; color: #1E40AF; font-size: 22px;">Halo, {{ $userName }}! 🎉</h2>
            <p style="font-size: 16px; line-height: 1.6; color: #4B5563;">
                Selamat! Pendaftaran anda telah disetujui oleh vendor. 
            </p>
            <p>Sekarang anda bisa mulai mengakses layanan <strong>SmartMBG</strong>.</p>
            <p style="font-size: 16px; line-height: 1.6; color: #4B5563;">
                Berikut adalah detail akun yang dapat anda gunakan untuk masuk ke dalam sistem:
            </p>

            <div style="background-color: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 20px; margin: 30px 0;">
                <table style="width: 100%; font-size: 16px;">
                    <tr>
                        <td style="padding: 8px 0; color: #6B7280; width: 100px;"><strong>Email</strong></td>
                        <td style="padding: 8px 0; color: #111827;">: {{ $userEmail }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #6B7280;"><strong>Password</strong></td>
                        <td style="padding: 8px 0; color: #111827; font-family: monospace; font-size: 18px; font-weight: bold;">: {{ $userPassword }}</td>
                    </tr>
                </table>
            </div>

            <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 15px; margin-bottom: 30px;">
                <p style="margin: 0; font-size: 14px; color: #991B1B;">
                    <strong>Penting:</strong> Harap segera mengubah password Anda setelah berhasil masuk pertama kali demi keamanan akun Anda.
                </p>
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <a href="http://192.168.101.8:8000/auth/login" style="background-color: #3B82F6; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; display: inline-block;">Masuk ke Aplikasi</a>
            </div>
        </div>

        <div style="background-color: #F3F4F6; padding: 20px; text-align: center; font-size: 13px; color: #9CA3AF;">
            <p style="margin: 0;">Email ini dibuat secara otomatis. Mohon tidak membalas email ini.</p>
            <p style="margin: 5px 0 0 0;">&copy; {{ date('Y') }} SmartMBG. Seluruh Hak Cipta Dilindungi.</p>
        </div>

    </div>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Permintaan Reset Password</h2>
    <p>Kami menerima permintaan untuk mereset password akun Anda.</p>
    <p>Gunakan kode OTP berikut ini untuk melanjutkan proses:</p>
    
    <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold; width: fit-content; margin: 20px 0;">
        {{ $otp }}
    </div>

    <p>Kode ini hanya berlaku selama 15 menit.</p>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
</body>
</html>

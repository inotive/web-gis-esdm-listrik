<!DOCTYPE html>
<html>

<head>
    <title>Permohonan Baru Masuk</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #2c3e50;">Pemberitahuan Permohonan Baru</h2>

        <p>Halo Admin,</p>

        <p>Terdapat pengajuan permohonan baru yang perlu ditinjau. Berikut adalah detail ringkasnya:</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; width: 30%;">Pemohon</td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $permohonanUser->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Jenis Permohonan</td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    {{ $permohonanUser->permohonan->jenis_permohonan ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Judul Permohonan</td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $permohonanUser->permohonan->nama ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Tanggal Pengajuan</td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    {{ $permohonanUser->created_at->format('d M Y H:i') }}</td>
            </tr>
        </table>

        <p>Silakan login ke panel admin untuk melihat detail lengkap dan memproses permohonan ini.</p>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.pengajuan-permohonan.index') }}"
                style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Lihat
                Permohonan</a>
        </div>

        <p style="margin-top: 30px; font-size: 12px; color: #777;">
            Ini adalah email otomatis, mohon tidak membalas email ini.
        </p>
    </div>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <title>Status Permohonan: {{ $status === 'selesai' ? 'Disetujui' : 'Ditolak' }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: {{ $status === 'selesai' ? '#27ae60' : '#e74c3c' }};">
            Permohonan Anda {{ $status === 'selesai' ? 'Disetujui' : 'Ditolak' }}
        </h2>

        <p>Halo {{ $permohonanUser->user->name ?? 'Pemohon' }},</p>

        <p>Permohonan Anda telah diproses oleh Admin. Berikut adalah detail ringkasnya:</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; width: 30%;">Jenis Permohonan
                </td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    {{ $permohonanUser->permohonan->jenis_permohonan ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Judul Permohonan</td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $permohonanUser->permohonan->nama ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Status</td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                    <span style="color: {{ $status === 'selesai' ? '#27ae60' : '#e74c3c' }}; font-weight: bold;">
                        {{ strtoupper($status) }}
                    </span>
                </td>
            </tr>
            @if ($permohonanUser->keterangan)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Keterangan Admin</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $permohonanUser->keterangan }}</td>
                </tr>
            @endif
        </table>

        @if ($status === 'selesai')
            <p>Selamat! Permohonan Anda telah disetujui. Anda dapat melihat detail dan mengunduh dokumen terkait di
                dashboard.</p>
        @else
            <p>Mohon maaf, permohonan Anda belum dapat disetujui saat ini. Silakan hubungi admin atau perbaiki data
                pengajuan Anda jika diperlukan.</p>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.pengajuan-permohonan.show', $permohonanUser->id) }}"
                style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Lihat
                Permohonan</a>
        </div>

        <p style="margin-top: 30px; font-size: 12px; color: #777;">
            Ini adalah email otomatis, mohon tidak membalas email ini.
        </p>
    </div>
</body>

</html>

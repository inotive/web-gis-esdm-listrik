@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
<head>
    <title>Rekap Elektrifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            border: none;
        }
    </style>
</head>
<body>
@endif

<table>
    <thead>
        <tr>
            <th colspan="15" style="text-align: center; font-weight: bold; font-size: 14px;">
                DATA RASIO DESA BERLISTRIK DAN RASIO ELEKTRIFIKASI
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center; font-weight: bold; font-size: 14px;">
                PER KABUPATEN/KOTA KALIMANTAN TIMUR
            </th>
        </tr>
        <tr>
            <th colspan="15" style="text-align: center; font-weight: bold; font-size: 14px;">
                Tahun {{ $tahun }}
            </th>
        </tr>
        @if(!isset($isPdf) || !$isPdf)
        <tr>
            <th colspan="15"></th>
        </tr>
        @endif
        <tr>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">No.</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Kabupaten/Kota</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Jumlah Desa</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Jumlah KK</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Jumlah Penduduk</th>
            <th colspan="3" style="font-weight: bold; text-align: center; border: 1px solid #000;">Desa Berlistrik</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Desa Belum Berlistrik</th>
            <th colspan="3" style="font-weight: bold; text-align: center; border: 1px solid #000;">KK Berlistrik</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Rasio Desa Berlistrik (%)</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Jumlah KK Belum Berlistrik</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; border: 1px solid #000; vertical-align: middle;">Rasio Elektrifikasi (%)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">PLN</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Non PLN</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Jumlah</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">PLN</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Non PLN</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $data)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $data['no'] }}</td>
                <td style="border: 1px solid #000;">{{ $data['kabupaten_kota'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jumlah_desa'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jumlah_kk'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jumlah_penduduk'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['desa_berlistrik_pln'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['desa_berlistrik_non_pln'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['desa_berlistrik_jumlah'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['desa_belum_berlistrik'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['kk_berlistrik_pln'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['kk_berlistrik_non_pln'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['kk_berlistrik_jumlah'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ number_format($data['rasio_desa_berlistrik'], 2) }}%</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jumlah_kk_belum_berlistrik'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ number_format($data['rasio_elektrifikasi'], 2) }}%</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold; text-align: center;">TOTAL KALTIM</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['jumlah_desa'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['jumlah_kk'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['jumlah_penduduk'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['desa_berlistrik_pln'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['desa_berlistrik_non_pln'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['desa_berlistrik_jumlah'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['desa_belum_berlistrik'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['kk_berlistrik_pln'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['kk_berlistrik_non_pln'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['kk_berlistrik_jumlah'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ number_format($total['rasio_desa_berlistrik'], 2) }}%</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $total['jumlah_kk_belum_berlistrik'] }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ number_format($total['rasio_elektrifikasi'], 2) }}%</td>
        </tr>
    </tfoot>
</table>

@if(isset($isPdf) && $isPdf)
</body>
</html>
@endif

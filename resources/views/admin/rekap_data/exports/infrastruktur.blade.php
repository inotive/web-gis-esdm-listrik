@if(isset($isPdf) && $isPdf)
<!DOCTYPE html>
<html>
<head>
    <title>Rekap Infrastruktur</title>
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
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px;">
                DATA INFRASTRUKTUR KETENAGALISTRIKAN
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px;">
                PER KABUPATEN/KOTA KALIMANTAN TIMUR
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px;">
                Rekap Perizinan
            </th>
        </tr>
        @if(!isset($isPdf) || !$isPdf)
        <tr>
            <th colspan="6"></th>
        </tr>
        @endif
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">No.</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Kota/Kabupaten</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Jumlah Perizinan</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">IUPTLS</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Rekomtek SKTP</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid #000;">Kapasitas (kVA)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($infrastrukturData as $data)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $data['no'] }}</td>
                <td style="border: 1px solid #000;">{{ $data['kabupaten_kota'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jumlah_perizinan'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['jumlah_iuptls'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $data['rekomtek_sktp'] }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ number_format($data['jumlah_kapasitas'], 2, '.', '') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="border: 1px solid #000; font-weight: bold; text-align: center;">TOTAL KALTIM</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $totalInfra['jumlah_perizinan'] ?? 0 }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $totalInfra['jumlah_iuptls'] ?? 0 }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ $totalInfra['rekomtek_sktp'] ?? 0 }}</td>
            <td style="border: 1px solid #000; font-weight: bold; text-align: right;">{{ number_format($totalInfra['jumlah_kapasitas'] ?? 0, 2, '.', '') }}</td>
        </tr>
    </tfoot>
</table>

@if(isset($isPdf) && $isPdf)
</body>
</html>
@endif

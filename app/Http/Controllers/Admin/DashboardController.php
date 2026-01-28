<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use App\Models\Gardu;
use App\Models\InfrastrukturJaringan;
use App\Models\PembangkitLokal;
use App\Models\Perusahaan;
use App\Models\Permohonan;
use App\Models\RekapElektrifikasi;
use App\Models\RencanaPengembanganBantuan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // ==========================================
        // STATISTIK WILAYAH
        // ==========================================

        // Ambil data kabupaten/kota di Kalimantan Timur (province_id = 64)
        $regencies = RegRegency::where('province_id', '64')
            ->withCount('districts')
            ->withCount('villages')
            ->orderBy('name')
            ->get();

        $totalKabKota = $regencies->count();
        $totalKecamatan = $regencies->sum('districts_count');
        $totalDesa = $regencies->sum('villages_count');

        // ==========================================
        // DATA ELEKTRIFIKASI PER KABUPATEN/KOTA
        // (Dari database RekapElektrifikasi)
        // ==========================================

        // Ambil tahun terbaru yang tersedia
        $availableYears = RekapElektrifikasi::getAvailableYears();
        $latestYear = $availableYears->isNotEmpty() ? $availableYears->first() : 2024;

        // Ambil data rekap untuk tahun terbaru
        $rekapData = RekapElektrifikasi::where('tahun', $latestYear)
            ->orderByRaw("FIELD(no_urut, 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X')")
            ->get();

        // Mapping ke format yang digunakan di view
        $elektrifikasiData = $rekapData->map(function ($item) {
            return [
                'name' => $item->kabupaten_kota,
                'desa_berlistrik' => $item->desa_berlistrik_jumlah,
                'desa_berlistrik_pln' => $item->desa_berlistrik_pln,
                'desa_berlistrik_non_pln' => $item->desa_berlistrik_non_pln,
                'desa_belum' => $item->desa_belum_berlistrik,
                'kk_berlistrik' => $item->kk_berlistrik_jumlah,
                'total_kk' => $item->jumlah_kk,
                'rasio' => $item->rasio_elektrifikasi,
            ];
        })->toArray();

        // Jika tidak ada data di database, gunakan data default
        if (empty($elektrifikasiData)) {
            $elektrifikasiData = [
                ['name' => 'Balikpapan', 'desa_berlistrik' => 34, 'desa_belum' => 0, 'kk_berlistrik' => 193587, 'total_kk' => 218833, 'rasio' => 88.46],
                ['name' => 'Berau', 'desa_berlistrik' => 110, 'desa_belum' => 0, 'kk_berlistrik' => 57806, 'total_kk' => 72644, 'rasio' => 79.57],
                ['name' => 'Bontang', 'desa_berlistrik' => 15, 'desa_belum' => 0, 'kk_berlistrik' => 46400, 'total_kk' => 55505, 'rasio' => 83.60],
                ['name' => 'Kutai Barat', 'desa_berlistrik' => 190, 'desa_belum' => 4, 'kk_berlistrik' => 41646, 'total_kk' => 48495, 'rasio' => 85.88],
                ['name' => 'Kutai Kartanegara', 'desa_berlistrik' => 236, 'desa_belum' => 1, 'kk_berlistrik' => 175200, 'total_kk' => 214437, 'rasio' => 81.70],
                ['name' => 'Kutai Timur', 'desa_berlistrik' => 141, 'desa_belum' => 0, 'kk_berlistrik' => 88853, 'total_kk' => 113573, 'rasio' => 78.23],
                ['name' => 'Mahakam Ulu', 'desa_berlistrik' => 45, 'desa_belum' => 5, 'kk_berlistrik' => 4277, 'total_kk' => 9027, 'rasio' => 47.38],
                ['name' => 'Paser', 'desa_berlistrik' => 143, 'desa_belum' => 1, 'kk_berlistrik' => 63749, 'total_kk' => 84326, 'rasio' => 75.60],
                ['name' => 'Penajam Paser Utara', 'desa_berlistrik' => 54, 'desa_belum' => 0, 'kk_berlistrik' => 41231, 'total_kk' => 52519, 'rasio' => 78.51],
                ['name' => 'Samarinda', 'desa_berlistrik' => 59, 'desa_belum' => 0, 'kk_berlistrik' => 244523, 'total_kk' => 246941, 'rasio' => 99.02],
            ];
        }

        // Hitung total statistik elektrifikasi dari database
        $totalStats = RekapElektrifikasi::getTotalByYear($latestYear);
        if ($totalStats) {
            $totalDesaBerlistrik = $totalStats['desa_berlistrik_jumlah'];
            $totalDesaBerlistrikPln = $totalStats['desa_berlistrik_pln'];
            $totalDesaBerlistrikNonPln = $totalStats['desa_berlistrik_non_pln'];
            $totalDesaBelum = $totalStats['desa_belum_berlistrik'];
            $totalKK = $totalStats['jumlah_kk'];
            $totalKKBerlistrik = $totalStats['kk_berlistrik_jumlah'];
            $rasioElektrifikasi = $totalStats['rasio_elektrifikasi'];
            $totalDesaRekap = $totalStats['jumlah_desa'];
        } else {
            $totalDesaBerlistrik = collect($elektrifikasiData)->sum('desa_berlistrik');
            $totalDesaBerlistrikPln = collect($elektrifikasiData)->sum('desa_berlistrik_pln');
            $totalDesaBerlistrikNonPln = collect($elektrifikasiData)->sum('desa_berlistrik_non_pln');
            $totalDesaBelum = collect($elektrifikasiData)->sum('desa_belum');
            $totalKK = collect($elektrifikasiData)->sum('total_kk');
            $totalKKBerlistrik = collect($elektrifikasiData)->sum('kk_berlistrik');
            $rasioElektrifikasi = $totalKK > 0 ? round(($totalKKBerlistrik / $totalKK) * 100, 2) : 0;
            $totalDesaRekap = $totalDesaBerlistrik + $totalDesaBelum;
        }

        // ==========================================
        // DATA INFRASTRUKTUR
        // ==========================================
        $totalGardu = Gardu::count();
        $totalJaringanKm = InfrastrukturJaringan::sum('panjang_jaringan');
        $totalPembangkit = PembangkitLokal::count();
        $totalPerusahaan = Perusahaan::count();

        // Gardu per jenis
        $garduPerJenis = Gardu::select('jenis_gardu_distribusi', DB::raw('count(*) as total'))
            ->groupBy('jenis_gardu_distribusi')
            ->orderByDesc('total')
            ->get();

        // Jaringan per tipe
        $jaringanPerTipe = InfrastrukturJaringan::select('jaringan', DB::raw('sum(panjang_jaringan) as total_km'))
            ->groupBy('jaringan')
            ->get();

        // ==========================================
        // INFRASTRUKTUR PER PERUSAHAAN
        // ==========================================
        // Get all companies with their infrastructure counts
        $perusahaanWithInfrastruktur = Perusahaan::with(['gardus', 'infrastrukturJaringans', 'pembangkitLokals'])
            ->get()
            ->map(function ($perusahaan) {
                return [
                    'id' => $perusahaan->id,
                    'nama' => $perusahaan->nama,
                    'jenis_usaha' => $perusahaan->jenis_usaha,
                    'kabupaten_kota' => $perusahaan->kabupaten_kota,
                    'total_gardu' => $perusahaan->gardus->count(),
                    'total_jaringan_km' => $perusahaan->infrastrukturJaringans->sum('panjang_jaringan'),
                    'total_pembangkit' => $perusahaan->pembangkitLokals->count(),
                    'total_infrastruktur' => $perusahaan->gardus->count() +
                                            $perusahaan->infrastrukturJaringans->count() +
                                            $perusahaan->pembangkitLokals->count(),
                ];
            })
            ->sortByDesc('total_infrastruktur')
            ->values();

        // Top 5 companies by infrastructure count
        $topPerusahaan = $perusahaanWithInfrastruktur->take(5);

        // Gardu per perusahaan (for pie chart)
        $garduPerPerusahaan = Perusahaan::withCount('gardus')
            ->having('gardus_count', '>', 0)
            ->get()
            ->map(function ($p) {
                return [
                    'nama' => $p->nama,
                    'total' => $p->gardus_count,
                ];
            });

        // Jaringan per perusahaan (for bar chart)
        $jaringanPerPerusahaan = Perusahaan::with('infrastrukturJaringans')
            ->get()
            ->map(function ($p) {
                $totalKm = $p->infrastrukturJaringans->sum('panjang_jaringan');
                return [
                    'nama' => $p->nama,
                    'total_km' => round($totalKm, 2),
                ];
            })
            ->filter(function ($item) {
                return $item['total_km'] > 0;
            })
            ->sortByDesc('total_km')
            ->values();

        // Pembangkit per perusahaan
        $pembangkitPerPerusahaan = Perusahaan::withCount('pembangkitLokals')
            ->having('pembangkit_lokals_count', '>', 0)
            ->get()
            ->map(function ($p) {
                return [
                    'nama' => $p->nama,
                    'total' => $p->pembangkit_lokals_count,
                ];
            });

        // ==========================================
        // TREND DATA (12 bulan terakhir - data statis)
        // ==========================================
        $trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $trendDesaBerlistrik = [980, 995, 1005, 1010, 1015, 1020, 1022, 1025, $totalDesaBerlistrik, $totalDesaBerlistrik, $totalDesaBerlistrik, $totalDesaBerlistrik];
        $trendRasio = [82.5, 83.1, 83.8, 84.2, 84.6, 84.9, 85.2, 85.5, $rasioElektrifikasi, $rasioElektrifikasi, $rasioElektrifikasi, $rasioElektrifikasi];

        // ==========================================
        // TOP INSIGHTS
        // ==========================================
        $topRasio = collect($elektrifikasiData)->sortByDesc('rasio')->take(3)->values();
        $lowRasio = collect($elektrifikasiData)->sortBy('rasio')->take(3)->values();
        $desaBelumBanyak = collect($elektrifikasiData)->where('desa_belum', '>', 0)->sortByDesc('desa_belum')->take(3)->values();

        // ==========================================
        // TOP 10 PRIORITAS (Rencana Pengembangan Bantuan)
        // ==========================================
        // $topPrioritas = RencanaPengembanganBantuan::with(['regency', 'district', 'village'])
        //     ->orderBy('total_skor', 'desc')
        //     ->take(10)
        //     ->get();
        $topPrioritas = collect([]);

        return view('admin.dashboard.index', [
            'title' => 'Dashboard Admin',
            // Statistik Utama
            'totalKabKota' => $totalKabKota,
            'totalKecamatan' => $totalKecamatan,
            'totalDesa' => $totalDesaRekap ?? $totalDesa,
            'totalDesaBerlistrik' => $totalDesaBerlistrik,
            'totalDesaBerlistrikPln' => $totalDesaBerlistrikPln,
            'totalDesaBerlistrikNonPln' => $totalDesaBerlistrikNonPln,
            'totalDesaBelum' => $totalDesaBelum,
            'rasioElektrifikasi' => $rasioElektrifikasi,
            'totalKK' => $totalKK,
            'totalKKBerlistrik' => $totalKKBerlistrik,
            // Infrastruktur
            'totalGardu' => $totalGardu,
            'totalJaringanKm' => $totalJaringanKm,
            'totalPembangkit' => $totalPembangkit,
            'totalPerusahaan' => $totalPerusahaan,
            'garduPerJenis' => $garduPerJenis,
            'jaringanPerTipe' => $jaringanPerTipe,
            // Company-Infrastructure Data
            'perusahaanWithInfrastruktur' => $perusahaanWithInfrastruktur,
            'topPerusahaan' => $topPerusahaan,
            'garduPerPerusahaan' => $garduPerPerusahaan,
            'jaringanPerPerusahaan' => $jaringanPerPerusahaan,
            'pembangkitPerPerusahaan' => $pembangkitPerPerusahaan,
            // Data per Kabupaten
            'elektrifikasiData' => $elektrifikasiData,
            'regencies' => $regencies,
            // Trend
            'trendLabels' => json_encode($trendLabels),
            'trendDesaBerlistrik' => json_encode($trendDesaBerlistrik),
            'trendRasio' => json_encode($trendRasio),
            // Insights
            'topRasio' => $topRasio,
            'lowRasio' => $lowRasio,
            'desaBelumBanyak' => $desaBelumBanyak,
            // Top 10 Prioritas
            'topPrioritas' => $topPrioritas,
            // Tahun data
            'tahunData' => $latestYear,
            // Permohonan Stats
            'totalPermohonan' => \App\Models\PermohonanUser::count(),
            'permohonanPending' => \App\Models\PermohonanUser::whereIn('status', ['pending', 'proses'])->count(),
            'permohonanApproved' => \App\Models\PermohonanUser::where('status', 'selesai')->count(),
            'permohonanRejected' => \App\Models\PermohonanUser::where('status', 'ditolak')->count(),
        ]);
    }
}

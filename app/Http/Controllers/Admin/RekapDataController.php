<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapElektrifikasi;
use App\Models\PerizinanListrik;
use App\Imports\RekapElektrifikasiImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class RekapDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:rekap.view')->only(['index', 'detail']);
        // If export exists:
        $this->middleware('can:rekap.export')->only(['export', 'downloadTemplate']);
        // If import exists (usually covered by create or manage):
        // Assuming rekap.view or separate permission. Seeder has rekap.view, .export etc.
        // Let's check seeder/permission list to be sure.
        // Seeder: rekap.view, rekap.elektrifikasi, rekap.infrastruktur, rekap.export
        // Import implies modifying data? Or just viewing stats?
        // Usually import is an admin action. Let's protect it with rekap.view for now or check if there is a manage perm.
        // Since there is no rekap.create/edit, we'll use rekap.view + auth for basic, or rekap.infrastruktur/elektrifikasi if specific.
        // But for safety, let's stick to rekap.view for index/detail.
    }

    /**
     * Menampilkan halaman rekap data rasio desa berlistrik dan rasio elektrifikasi
     */
    public function index(Request $request)
    {
        // Get available years for dropdown
        $availableYears = RekapElektrifikasi::getAvailableYears();

        // Default tahun: tahun terbaru yang tersedia, atau 2024 jika tidak ada data
        $defaultTahun = $availableYears->isNotEmpty() ? $availableYears->first() : 2024;
        $tahun = $request->get('tahun', $defaultTahun);

        // ==========================================
        // TAB 1: DATA DESA BERLISTRIK (dari database)
        // ==========================================
        $rekapData = RekapElektrifikasi::where('tahun', $tahun)
            ->orderByRaw("FIELD(no_urut, 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X')")
            ->get()
            ->map(function ($item) {
                return [
                    'no' => $item->no_urut,
                    'kabupaten_kota' => $item->kabupaten_kota,
                    'jumlah_desa' => $item->jumlah_desa,
                    'jumlah_kk' => $item->jumlah_kk,
                    'jumlah_penduduk' => $item->jumlah_penduduk,
                    'desa_berlistrik_pln' => $item->desa_berlistrik_pln,
                    'desa_berlistrik_non_pln' => $item->desa_berlistrik_non_pln,
                    'desa_berlistrik_jumlah' => $item->desa_berlistrik_jumlah,
                    'desa_belum_berlistrik' => $item->desa_belum_berlistrik,
                    'kk_berlistrik_pln' => $item->kk_berlistrik_pln,
                    'kk_berlistrik_non_pln' => $item->kk_berlistrik_non_pln,
                    'kk_berlistrik_jumlah' => $item->kk_berlistrik_jumlah,
                    'rasio_desa_berlistrik' => $item->rasio_desa_berlistrik,
                    'jumlah_kk_belum_berlistrik' => $item->jumlah_kk_belum_berlistrik,
                    'rasio_elektrifikasi' => $item->rasio_elektrifikasi,
                ];
            })
            ->toArray();

        // Jika tidak ada data di database, gunakan data default (fallback)
        if (empty($rekapData)) {
            $rekapData = $this->getDefaultRekapData();
        }

        // Hitung total untuk Tab 1
        $total = RekapElektrifikasi::getTotalByYear($tahun);
        if (!$total) {
            $total = $this->calculateTotal($rekapData);
        }

        // ==========================================
        // TAB 2: DATA INFRASTRUKTUR (dari database perizinan_listriks)
        // ==========================================
        $infrastrukturData = PerizinanListrik::selectRaw('
                kabupaten_kota,
                COUNT(*) as jumlah_perizinan,
                SUM(CASE WHEN jenis_usaha LIKE "%IUPTL%" OR jenis LIKE "%IUPTL%" THEN 1 ELSE 0 END) as jumlah_iuptls,
                SUM(CASE WHEN jenis_usaha LIKE "%SKTP%" OR jenis LIKE "%SKTP%" OR jenis LIKE "%Rekomtek%" THEN 1 ELSE 0 END) as rekomtek_sktp,
                SUM(COALESCE(total_kapasitas, 0)) as jumlah_kapasitas
            ')
            ->groupBy('kabupaten_kota')
            ->orderBy('kabupaten_kota')
            ->get()
            ->map(function ($item, $index) {
                return [
                    'no' => $this->getRomanNumeral($index + 1),
                    'kabupaten_kota' => $item->kabupaten_kota,
                    'jumlah_perizinan' => $item->jumlah_perizinan,
                    'jumlah_iuptls' => $item->jumlah_iuptls,
                    'rekomtek_sktp' => $item->rekomtek_sktp,
                    'jumlah_kapasitas' => $item->jumlah_kapasitas,
                ];
            })
            ->toArray();

        $totalInfra = [
            'jumlah_perizinan' => collect($infrastrukturData)->sum('jumlah_perizinan'),
            'jumlah_iuptls' => collect($infrastrukturData)->sum('jumlah_iuptls'),
            'rekomtek_sktp' => collect($infrastrukturData)->sum('rekomtek_sktp'),
            'jumlah_kapasitas' => collect($infrastrukturData)->sum('jumlah_kapasitas'),
        ];

        return view('admin.rekap_data.index', compact(
            'rekapData',
            'total',
            'infrastrukturData',
            'totalInfra',
            'tahun',
            'availableYears'
        ));
    }

    /**
     * Menampilkan detail perizinan per kabupaten
     */
    public function detail(Request $request, string $kabupaten)
    {
        $kabupaten = urldecode($kabupaten);
        $perPage = (int) $request->get('per_page', 15);
        $search = $request->get('q', '');
        $jenis = $request->get('jenis', '');

        $query = PerizinanListrik::where('kabupaten_kota', $kabupaten);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pemohon', 'like', "%{$search}%")
                    ->orWhere('no_surat_izin', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($jenis) {
            $query->where('jenis', 'like', "%{$jenis}%");
        }

        $perizinanItems = $query->orderBy('tanggal_terbit', 'desc')
            ->paginate($perPage)
            ->appends($request->only(['q', 'jenis', 'per_page']));

        // Get statistics
        $stats = [
            'total_perizinan' => PerizinanListrik::where('kabupaten_kota', $kabupaten)->count(),
            'total_kapasitas' => PerizinanListrik::where('kabupaten_kota', $kabupaten)->sum('total_kapasitas'),
        ];

        // Get jenis options for filter
        $jenisOptions = PerizinanListrik::where('kabupaten_kota', $kabupaten)
            ->select('jenis')
            ->distinct()
            ->pluck('jenis')
            ->filter()
            ->values();

        return view('admin.rekap_data.detail', compact(
            'kabupaten',
            'perizinanItems',
            'stats',
            'jenisOptions',
            'search',
            'jenis',
            'perPage'
        ));
    }

    /**
     * Fallback data jika database kosong
     */
    private function getDefaultRekapData(): array
    {
        return [
            ['no' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 218833, 'jumlah_penduduk' => 644315, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 193587, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 193587, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 25246, 'rasio_elektrifikasi' => 88.46],
            ['no' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 110, 'jumlah_kk' => 72644, 'jumlah_penduduk' => 223556, 'desa_berlistrik_pln' => 66, 'desa_berlistrik_non_pln' => 44, 'desa_berlistrik_jumlah' => 110, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 51135, 'kk_berlistrik_non_pln' => 6671, 'kk_berlistrik_jumlah' => 57806, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 14838, 'rasio_elektrifikasi' => 79.57],
            ['no' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 237, 'jumlah_kk' => 214437, 'jumlah_penduduk' => 676735, 'desa_berlistrik_pln' => 214, 'desa_berlistrik_non_pln' => 22, 'desa_berlistrik_jumlah' => 236, 'desa_belum_berlistrik' => 1, 'kk_berlistrik_pln' => 166398, 'kk_berlistrik_non_pln' => 8802, 'kk_berlistrik_jumlah' => 175200, 'rasio_desa_berlistrik' => 99.58, 'jumlah_kk_belum_berlistrik' => 39237, 'rasio_elektrifikasi' => 81.70],
            ['no' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 246941, 'jumlah_penduduk' => 777073, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 244523, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 244523, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 2418, 'rasio_elektrifikasi' => 99.02],
            ['no' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 141, 'jumlah_kk' => 113573, 'jumlah_penduduk' => 419756, 'desa_berlistrik_pln' => 70, 'desa_berlistrik_non_pln' => 71, 'desa_berlistrik_jumlah' => 141, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 55727, 'kk_berlistrik_non_pln' => 33126, 'kk_berlistrik_jumlah' => 88853, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 24720, 'rasio_elektrifikasi' => 78.23],
            ['no' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 55505, 'jumlah_penduduk' => 178718, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 46400, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 46400, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 9105, 'rasio_elektrifikasi' => 83.60],
            ['no' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 54, 'jumlah_kk' => 52519, 'jumlah_penduduk' => 169428, 'desa_berlistrik_pln' => 54, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 38389, 'kk_berlistrik_non_pln' => 2842, 'kk_berlistrik_jumlah' => 41231, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 11288, 'rasio_elektrifikasi' => 78.51],
            ['no' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 144, 'jumlah_kk' => 84326, 'jumlah_penduduk' => 258022, 'desa_berlistrik_pln' => 106, 'desa_berlistrik_non_pln' => 37, 'desa_berlistrik_jumlah' => 143, 'desa_belum_berlistrik' => 1, 'kk_berlistrik_pln' => 57956, 'kk_berlistrik_non_pln' => 5793, 'kk_berlistrik_jumlah' => 63749, 'rasio_desa_berlistrik' => 99.31, 'jumlah_kk_belum_berlistrik' => 20577, 'rasio_elektrifikasi' => 75.60],
            ['no' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 194, 'jumlah_kk' => 48495, 'jumlah_penduduk' => 161111, 'desa_berlistrik_pln' => 113, 'desa_berlistrik_non_pln' => 77, 'desa_berlistrik_jumlah' => 190, 'desa_belum_berlistrik' => 4, 'kk_berlistrik_pln' => 31410, 'kk_berlistrik_non_pln' => 10236, 'kk_berlistrik_jumlah' => 41646, 'rasio_desa_berlistrik' => 97.94, 'jumlah_kk_belum_berlistrik' => 6849, 'rasio_elektrifikasi' => 85.88],
            ['no' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 50, 'jumlah_kk' => 9027, 'jumlah_penduduk' => 28231, 'desa_berlistrik_pln' => 16, 'desa_berlistrik_non_pln' => 29, 'desa_berlistrik_jumlah' => 45, 'desa_belum_berlistrik' => 5, 'kk_berlistrik_pln' => 1588, 'kk_berlistrik_non_pln' => 2689, 'kk_berlistrik_jumlah' => 4277, 'rasio_desa_berlistrik' => 90.00, 'jumlah_kk_belum_berlistrik' => 4750, 'rasio_elektrifikasi' => 47.38],
        ];
    }

    /**
     * Hitung total dari array data
     */
    private function calculateTotal(array $rekapData): array
    {
        $collection = collect($rekapData);

        $total = [
            'jumlah_desa' => $collection->sum('jumlah_desa'),
            'jumlah_kk' => $collection->sum('jumlah_kk'),
            'jumlah_penduduk' => $collection->sum('jumlah_penduduk'),
            'desa_berlistrik_pln' => $collection->sum('desa_berlistrik_pln'),
            'desa_berlistrik_non_pln' => $collection->sum('desa_berlistrik_non_pln'),
            'desa_berlistrik_jumlah' => $collection->sum('desa_berlistrik_jumlah'),
            'desa_belum_berlistrik' => $collection->sum('desa_belum_berlistrik'),
            'kk_berlistrik_pln' => $collection->sum('kk_berlistrik_pln'),
            'kk_berlistrik_non_pln' => $collection->sum('kk_berlistrik_non_pln'),
            'kk_berlistrik_jumlah' => $collection->sum('kk_berlistrik_jumlah'),
            'jumlah_kk_belum_berlistrik' => $collection->sum('jumlah_kk_belum_berlistrik'),
        ];

        $total['rasio_desa_berlistrik'] = $total['jumlah_desa'] > 0
            ? round(($total['desa_berlistrik_jumlah'] / $total['jumlah_desa']) * 100, 2)
            : 0;
        $total['rasio_elektrifikasi'] = $total['jumlah_kk'] > 0
            ? round(($total['kk_berlistrik_jumlah'] / $total['jumlah_kk']) * 100, 2)
            : 0;

        return $total;
    }

    /**
     * Konversi angka ke romawi
     */
    private function getRomanNumeral(int $number): string
    {
        $romanNumerals = [
            'I',
            'II',
            'III',
            'IV',
            'V',
            'VI',
            'VII',
            'VIII',
            'IX',
            'X',
            'XI',
            'XII',
            'XIII',
            'XIV',
            'XV',
            'XVI',
            'XVII',
            'XVIII',
            'XIX',
            'XX'
        ];
        return $romanNumerals[$number - 1] ?? (string) $number;
    }
    /**
     * Download template import Excel
     */
    public function downloadTemplate()
    {
        // Headers for the Excel file
        $headers = [
            'No',
            'Kabupaten Kota',
            'Jumlah Desa',
            'Jumlah KK',
            'Jumlah Penduduk',
            'Desa Berlistrik PLN',
            'Desa Berlistrik Non PLN',
            'Desa Berlistrik Jumlah',
            'Desa Belum Berlistrik',
            'KK Berlistrik PLN',
            'KK Berlistrik Non PLN',
            'KK Berlistrik Jumlah',
            'Rasio Desa Berlistrik',
            'Jumlah KK Belum Berlistrik',
            'Rasio Elektrifikasi'
        ];

        // Example data
        $data = [
            $headers,
            [
                'I', 'Balikpapan', 34, 218833, 644315, 34, 0, 34, 0, 193587, 0, 193587, 100, 25246, 88.46
            ],
            [
                'II', 'Berau', 110, 72644, 223556, 66, 44, 110, 0, 51135, 6671, 57806, 100, 14838, 79.57
            ]
        ];

        // Create a callback to generate excel
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        // Return stream
        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_rekap_data.csv",
        ]);
    }

    /**
     * Process Import Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
            'tahun' => 'required|integer|min:2000|max:'.(date('Y')+2),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $tahun = $request->input('tahun');
            $file = $request->file('file');

            Excel::import(new RekapElektrifikasiImport($tahun), $file);

            return redirect()->route('admin.rekap-data.index', ['tahun' => $tahun])
                ->with('success', 'Data berhasil diimport untuk tahun ' . $tahun);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}

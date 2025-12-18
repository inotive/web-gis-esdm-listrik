<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfrastrukturJaringan;
use App\Models\Gardu;
use App\Models\PembangkitLokal;
use App\Models\LN_Transmisi;
use App\Models\LN_SUTM_Berau;
use App\Models\PT_Pembangkit_Eksisting;
use Illuminate\Http\Request;

class DataInfrastrukturController extends Controller
{
    /**
     * Menampilkan halaman gabungan Data Infrastruktur dengan tabs
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $tab = $request->get('tab', 'jaringan');

        // ===========================================
        // TAB 1: INFRASTRUKTUR JARINGAN (Transmisi)
        // ===========================================
        $qJaringan = $request->get('q_jaringan', '');
        $jaringan = $request->get('jaringan', '');

        // Cek apakah tabel infrastruktur_jaringan memiliki data
        $useGisData = InfrastrukturJaringan::count() == 0;

        if ($useGisData) {
            // Gunakan data dari LN_Transmisi
            $queryJaringan = LN_Transmisi::query();
            if ($qJaringan) {
                $queryJaringan->where(function ($q) use ($qJaringan) {
                    $q->where('namobj', 'like', "%{$qJaringan}%")
                        ->orWhere('jnsrsr', 'like', "%{$qJaringan}%")
                        ->orWhere('wadmpr', 'like', "%{$qJaringan}%");
                });
            }
            $jaringanItems = $queryJaringan->orderBy('objectid', 'asc')->paginate($perPage, ['*'], 'page_jaringan');
        } else {
            $queryJaringan = InfrastrukturJaringan::query();
            if ($qJaringan) {
                $queryJaringan->where(function ($q) use ($qJaringan) {
                    $q->where('jenis', 'like', "%{$qJaringan}%")
                        ->orWhere('jaringan', 'like', "%{$qJaringan}%");
                });
            }
            if ($jaringan) {
                $queryJaringan->where('jaringan', $jaringan);
            }
            $jaringanItems = $queryJaringan->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page_jaringan');
        }
        $jaringanItems->appends($request->only(['q_jaringan', 'jaringan', 'tab', 'per_page']));

        $jaringanOptions = [
            'transmisi' => 'Transmisi',
            'distribusi' => 'Distribusi',
        ];

        // ===========================================
        // TAB 2: DATA GARDU (SUTM - Saluran Udara Tegangan Menengah)
        // ===========================================
        $qGardu = $request->get('q_gardu', '');
        $jenisGardu = $request->get('jenis_gardu', '');

        // Cek apakah tabel gardus memiliki data
        $useGisGardu = Gardu::count() == 0;

        if ($useGisGardu) {
            // Gunakan data dari LN_SUTM_Berau sebagai sample data gardu/jaringan distribusi
            $queryGardu = LN_SUTM_Berau::query();
            if ($qGardu) {
                $queryGardu->where(function ($q) use ($qGardu) {
                    $q->where('descriptio', 'like', "%{$qGardu}%")
                        ->orWhere('location', 'like', "%{$qGardu}%")
                        ->orWhere('penyulang', 'like', "%{$qGardu}%");
                });
            }
            if ($jenisGardu) {
                $queryGardu->where('mainline', $jenisGardu);
            }
            $garduItems = $queryGardu->orderBy('objectid', 'asc')->paginate($perPage, ['*'], 'page_gardu');
            $jenisGarduOptions = LN_SUTM_Berau::select('mainline')->distinct()->pluck('mainline')->filter()->toArray();
        } else {
            $queryGardu = Gardu::query();
            if ($qGardu) {
                $queryGardu->where(function ($q) use ($qGardu) {
                    $q->where('nama', 'like', "%{$qGardu}%")
                        ->orWhereHas('wilayah', function ($wq) use ($qGardu) {
                            $wq->where('nama', 'like', "%{$qGardu}%");
                        });
                });
            }
            if ($jenisGardu) {
                $queryGardu->where('jenis_gardu_distribusi', $jenisGardu);
            }
            $garduItems = $queryGardu->with('wilayah')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page_gardu');
            $jenisGarduOptions = Gardu::select('jenis_gardu_distribusi')->distinct()->pluck('jenis_gardu_distribusi')->filter()->toArray();
        }
        $garduItems->appends($request->only(['q_gardu', 'jenis_gardu', 'tab', 'per_page']));

        // ===========================================
        // TAB 3: PEMBANGKIT LISTRIK (Eksisting)
        // ===========================================
        $qPembangkit = $request->get('q_pembangkit', '');

        // Cek apakah tabel pembangkit_lokals memiliki data
        $useGisPembangkit = PembangkitLokal::count() == 0;

        if ($useGisPembangkit) {
            // Gunakan data dari PT_Pembangkit_Eksisting
            $queryPembangkit = PT_Pembangkit_Eksisting::query();
            if ($qPembangkit) {
                $queryPembangkit->where(function ($q) use ($qPembangkit) {
                    $q->where('namobj', 'like', "%{$qPembangkit}%")
                        ->orWhere('wadmpr', 'like', "%{$qPembangkit}%")
                        ->orWhere('j_pmbngkt', 'like', "%{$qPembangkit}%");
                });
            }
            $pembangkitItems = $queryPembangkit->orderBy('objectid', 'asc')->paginate($perPage, ['*'], 'page_pembangkit');
        } else {
            $queryPembangkit = PembangkitLokal::query();
            if ($qPembangkit) {
                $queryPembangkit->where(function ($q) use ($qPembangkit) {
                    $q->where('kapasitas_gardu', 'like', "%{$qPembangkit}%")
                        ->orWhereHas('wilayah', function ($wq) use ($qPembangkit) {
                            $wq->where('nama', 'like', "%{$qPembangkit}%");
                        });
                });
            }
            $pembangkitItems = $queryPembangkit->with('wilayah')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page_pembangkit');
        }
        $pembangkitItems->appends($request->only(['q_pembangkit', 'tab', 'per_page']));

        return view('admin.data_infrastruktur.index', [
            'tab' => $tab,
            'perPage' => $perPage,
            // Jaringan
            'jaringanItems' => $jaringanItems,
            'jaringanOptions' => $jaringanOptions,
            'qJaringan' => $qJaringan,
            'jaringanFilter' => $jaringan,
            'useGisJaringan' => $useGisData,
            // Gardu
            'garduItems' => $garduItems,
            'jenisGarduOptions' => $jenisGarduOptions,
            'qGardu' => $qGardu,
            'jenisGarduFilter' => $jenisGardu,
            'useGisGardu' => $useGisGardu,
            // Pembangkit
            'pembangkitItems' => $pembangkitItems,
            'qPembangkit' => $qPembangkit,
            'useGisPembangkit' => $useGisPembangkit,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfrastrukturJaringan;
use App\Models\Gardu;
use App\Models\PembangkitLokal;
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
        // TAB 1: INFRASTRUKTUR JARINGAN
        // ===========================================
        $qJaringan = $request->get('q_jaringan', '');
        $jaringan = $request->get('jaringan', '');

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
        $jaringanItems->appends($request->only(['q_jaringan', 'jaringan', 'tab', 'per_page']));

        $jaringanOptions = [
            'transmisi' => 'Transmisi',
            'distribusi' => 'Distribusi',
        ];

        // ===========================================
        // TAB 2: DATA GARDU
        // ===========================================
        $qGardu = $request->get('q_gardu', '');
        $jenisGardu = $request->get('jenis_gardu', '');

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
        $garduItems->appends($request->only(['q_gardu', 'jenis_gardu', 'tab', 'per_page']));

        $jenisGarduOptions = Gardu::select('jenis_gardu_distribusi')->distinct()->pluck('jenis_gardu_distribusi')->filter()->toArray();

        // ===========================================
        // TAB 3: PEMBANGKIT LOKAL
        // ===========================================
        $qPembangkit = $request->get('q_pembangkit', '');

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
        $pembangkitItems->appends($request->only(['q_pembangkit', 'tab', 'per_page']));

        return view('admin.data_infrastruktur.index', [
            'tab' => $tab,
            'perPage' => $perPage,
            // Jaringan
            'jaringanItems' => $jaringanItems,
            'jaringanOptions' => $jaringanOptions,
            'qJaringan' => $qJaringan,
            'jaringanFilter' => $jaringan,
            // Gardu
            'garduItems' => $garduItems,
            'jenisGarduOptions' => $jenisGarduOptions,
            'qGardu' => $qGardu,
            'jenisGarduFilter' => $jenisGardu,
            // Pembangkit
            'pembangkitItems' => $pembangkitItems,
            'qPembangkit' => $qPembangkit,
        ]);
    }
}

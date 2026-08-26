<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RencanaPengembanganBantuan;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RencanaPengembanganController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Illuminate\Support\Facades\Auth::user()->can('rencana_pengembangan.view'), 403, 'Unauthorized');

        $query = RencanaPengembanganBantuan::with(['regency', 'district', 'village']);

        // Top filter bar filters
        if ($request->filled('regency_id')) {
            $query->where('regency_id', $request->regency_id);
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('village_id')) {
            $query->where('village_id', $request->village_id);
        }

        if ($request->filled('prioritas')) {
            $prioritas = $request->prioritas;
            if ($prioritas == 1) {
                $query->whereBetween('total_skor', [19, 25]);
            } elseif ($prioritas == 2) {
                $query->whereBetween('total_skor', [15, 18]);
            } elseif ($prioritas == 3) {
                $query->whereBetween('total_skor', [5, 14]);
            }
        }

        // Table row filters
        if ($request->filled('filter_regency_id')) {
            $query->where('regency_id', $request->filter_regency_id);
        }

        if ($request->filled('filter_district_id')) {
            $query->where('district_id', $request->filter_district_id);
        }

        if ($request->filled('filter_village')) {
            $query->whereHas('village', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->filter_village . '%');
            });
        }

        if ($request->filled('filter_min_pelanggan')) {
            $query->where('jumlah_calon_pelanggan', '>=', $request->filter_min_pelanggan);
        }

        if ($request->filled('filter_aksesibilitas')) {
            $query->where('aksesibilitas', $request->filter_aksesibilitas);
        }

        if ($request->filled('filter_radius_jaringan')) {
            $query->where('radius_jaringan', $request->filter_radius_jaringan);
        }

        if ($request->filled('filter_arah_kebijakan')) {
            $query->where('arah_kebijakan', $request->filter_arah_kebijakan);
        }

        if ($request->filled('filter_potensi_kegiatan')) {
            $query->where('potensi_kegiatan', $request->filter_potensi_kegiatan);
        }

        if ($request->filled('filter_jumlah_pelanggan')) {
            $query->where('jumlah_pelanggan', $request->filter_jumlah_pelanggan);
        }

        if ($request->filled('filter_prioritas')) {
            $prioritas = $request->filter_prioritas;
            if ($prioritas == 1) {
                $query->whereBetween('total_skor', [19, 25]);
            } elseif ($prioritas == 2) {
                $query->whereBetween('total_skor', [15, 18]);
            } elseif ($prioritas == 3) {
                $query->whereBetween('total_skor', [5, 14]);
            }
        }

        if ($request->filled('filter_rencana_sumber_listrik')) {
            $query->where('rencana_sumber_listrik', $request->filter_rencana_sumber_listrik);
        }

        // Search
        if ($request->filled('q')) {
            $query->whereHas('village', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        // Order by total score descending (highest first), then by village name
        $query->orderBy('total_skor', 'desc')
            ->orderBy('regency_id', 'asc')
            ->orderBy('district_id', 'asc')
            ->orderBy('village_id', 'asc');

        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);
        $districts = RegDistrict::orderBy('name')->get(['id', 'name']);
        $prioritasOptions = [1, 2, 3];
        $sumberListrikOptions = ['SUTM', 'PLTS'];

        // Get unique values from database for filters (maintain score order 5 to 1)
        $uniqueAksesibilitas = collect(array_keys(RencanaPengembanganBantuan::getAksesibilitasOptions()));

        $uniqueRadiusJaringan = collect(array_keys(RencanaPengembanganBantuan::getRadiusJaringanOptions()));

        $uniqueArahKebijakan = collect(array_keys(RencanaPengembanganBantuan::getArahKebijakanOptions()));

        $uniquePotensiKegiatan = collect(array_keys(RencanaPengembanganBantuan::getPotensiKegiatanOptions()));

        $uniqueJumlahPelanggan = collect(array_keys(RencanaPengembanganBantuan::getJumlahPelangganOptions()));

        // Get dropdown options
        $aksesibilitasOptions = RencanaPengembanganBantuan::getAksesibilitasOptions();
        $radiusOptions = RencanaPengembanganBantuan::getRadiusJaringanOptions();
        $kebijakanOptions = RencanaPengembanganBantuan::getArahKebijakanOptions();
        $potensiOptions = RencanaPengembanganBantuan::getPotensiKegiatanOptions();
        $pelangganOptions = RencanaPengembanganBantuan::getJumlahPelangganOptions();

        return view('admin.rencana_pengembangan.index', compact(
            'data',
            'regencies',
            'districts',
            'prioritasOptions',
            'aksesibilitasOptions',
            'radiusOptions',
            'kebijakanOptions',
            'potensiOptions',
            'pelangganOptions',
            'sumberListrikOptions',
            'uniqueAksesibilitas',
            'uniqueRadiusJaringan',
            'uniqueArahKebijakan',
            'uniquePotensiKegiatan',
            'uniqueJumlahPelanggan'
        ));
    }

    public function updateField(Request $request, $id)
    {
        abort_unless(\Illuminate\Support\Facades\Auth::user()->can('rencana_pengembangan.edit'), 403, 'Unauthorized');

        $item = RencanaPengembanganBantuan::findOrFail($id);

        $field = $request->input('field');
        $value = $request->input('value');

        // Update the field
        $item->{$field} = $value;

        // Update corresponding score based on field
        if ($field === 'aksesibilitas') {
            $options = RencanaPengembanganBantuan::getAksesibilitasOptions();
            $item->skor_aksesibilitas = $options[$value] ?? 0;
        } elseif ($field === 'radius_jaringan') {
            $options = RencanaPengembanganBantuan::getRadiusJaringanOptions();
            $item->skor_radius = $options[$value] ?? 0;
        } elseif ($field === 'arah_kebijakan') {
            $options = RencanaPengembanganBantuan::getArahKebijakanOptions();
            $item->skor_arah_kebijakan = $options[$value] ?? 0;
        } elseif ($field === 'potensi_kegiatan') {
            $options = RencanaPengembanganBantuan::getPotensiKegiatanOptions();
            $item->skor_potensi_kegiatan = $options[$value] ?? 0;
        } elseif ($field === 'jumlah_pelanggan') {
            $options = RencanaPengembanganBantuan::getJumlahPelangganOptions();
            $item->skor_jumlah_pelanggan = $options[$value] ?? 0;
        }

        // Recalculate total score
        $item->calculateTotalSkor();
        $item->save();

        return response()->json([
            'success' => true,
            'total_skor' => $item->total_skor,
            'skor_aksesibilitas' => $item->skor_aksesibilitas,
            'skor_radius' => $item->skor_radius,
            'skor_arah_kebijakan' => $item->skor_arah_kebijakan,
            'skor_potensi_kegiatan' => $item->skor_potensi_kegiatan,
            'skor_jumlah_pelanggan' => $item->skor_jumlah_pelanggan,
            'rencana_sumber_listrik' => $item->rencana_sumber_listrik,
            'prioritas' => $item->prioritas,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(\Illuminate\Support\Facades\Auth::user()->can('rencana_pengembangan.create'), 403, 'Unauthorized');

        $validated = $request->validate([
            'regency_id' => 'required|exists:reg_regencies,id',
            'district_id' => 'required|exists:reg_districts,id',
            'village_id' => 'required|exists:reg_villages,id',
            'jumlah_calon_pelanggan' => 'nullable|integer',
        ]);

        $item = RencanaPengembanganBantuan::create($validated);

        return redirect()->route('admin.rencana-pengembangan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        abort_unless(\Illuminate\Support\Facades\Auth::user()->can('rencana_pengembangan.delete'), 403, 'Unauthorized');

        $item = RencanaPengembanganBantuan::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.rencana-pengembangan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}

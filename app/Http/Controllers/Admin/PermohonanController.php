<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\PermohonanQuestion;
use App\Models\PermohonanQuestionOption;
use App\Models\Perizinan;
use App\Models\PerizinanListrik;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PermohonanImport;
use App\Exports\PermohonanTemplateExport;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $tab = $request->get('tab', 'permohonan');
        $status = $request->get('status', '');
        $jenis = $request->get('jenis', '');

        // Per-column filters for Perizinan tab
        $filterPerizinanNama = $request->get('filter_perizinan_nama');
        $filterPerizinanKabupaten = $request->get('filter_perizinan_kabupaten');
        $filterPerizinanJenis = $request->get('filter_perizinan_jenis');
        $filterPerizinanNoIzin = $request->get('filter_perizinan_no_izin');
        $filterPerizinanTglTerbit = $request->get('filter_perizinan_tgl_terbit');
        $filterPerizinanTglAkhir = $request->get('filter_perizinan_tgl_akhir');
        $filterPerizinanStatus = $request->get('filter_perizinan_status');
        $filterPerizinanKapasitas = $request->get('filter_perizinan_kapasitas');

        // Per-column filters for Permohonan tab
        $filterPermohonanPengguna = $request->get('filter_permohonan_pengguna');
        $filterPermohonanKategori = $request->get('filter_permohonan_kategori');
        $filterPermohonanStatus = $request->get('filter_permohonan_status');
        $filterPermohonanTanggal = $request->get('filter_permohonan_tanggal');

        $query = Permohonan::withCount('questions');

        // if ($q) {
        //     $query->whereHas('user', function ($sub) use ($q) {
        //         $sub->where('name', 'like', "%{$q}%");
        //     })->orWhereHas('permohonan', function ($sub) use ($q) {
        //         $sub->where('nama', 'like', "%{$q}%");
        //     });
        // }

        // =========================================
        // TAB 2: Data Permohonan dari PermohonanUser
        // =========================================
        $queryPermohonanUser = PermohonanUser::with(['permohonan', 'user']);

        // Check if any per-column filters are active for Permohonan
        $hasPerColumnFiltersPermohonan = $filterPermohonanPengguna || $filterPermohonanKategori ||
            $filterPermohonanStatus || $filterPermohonanTanggal;

        // Per-column filters for Permohonan tab
        if ($filterPermohonanPengguna) {
            $queryPermohonanUser->whereHas('user', function ($subQuery) use ($filterPermohonanPengguna) {
                $subQuery->where('name', 'like', '%' . $filterPermohonanPengguna . '%');
            });
        }

        if ($filterPermohonanKategori) {
            $queryPermohonanUser->whereHas('permohonan', function ($subQuery) use ($filterPermohonanKategori) {
                $subQuery->where('nama', 'like', '%' . $filterPermohonanKategori . '%');
            });
        }

        if ($filterPermohonanStatus) {
            $queryPermohonanUser->where('status', 'like', '%' . $filterPermohonanStatus . '%');
        }

        if ($filterPermohonanTanggal) {
            $queryPermohonanUser->whereDate('created_at', $filterPermohonanTanggal);
        }

        $permohonanUsers = $queryPermohonanUser
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page_permohonan_user')
            ->withQueryString();

        // Untuk filter dropdown (tidak digunakan untuk filtering, hanya UI)
        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);

        // =========================================
        // TAB 3: Data Perizinan (Merged from PerizinanController)
        // =========================================
        $queryPerizinan = Perizinan::with('perusahaan');

        if ($request->has('q')) {
            $q = $request->q;
            $queryPerizinan->where(function($sub) use ($q) {
                $sub->where('nama_perusahaan', 'like', "%{$q}%")
                    ->orWhere('no_pengajuan', 'like', "%{$q}%")
                    ->orWhere('jenis', 'like', "%{$q}%")
                    ->orWhereHas('perusahaan', function($p) use ($q) {
                        $p->where('nama', 'like', "%{$q}%");
                    });
            });
        }


        if ($filterPerizinanNama) {
            $queryPerizinan->where(function($q) use ($filterPerizinanNama) {
                $q->where('nama_perusahaan', 'like', "%{$filterPerizinanNama}%")
                  ->orWhereHas('perusahaan', function($sub) use ($filterPerizinanNama) {
                      $sub->where('nama', 'like', "%{$filterPerizinanNama}%");
                  });
            });
        }
        
        // Filter Kabupaten/Kota
        if ($filterPerizinanKabupaten) {
            $queryPerizinan->whereHas('perusahaan', function($subQuery) use ($filterPerizinanKabupaten) {
                $subQuery->where('kabupaten_kota', 'like', "%{$filterPerizinanKabupaten}%");
            });
        }
        
        // Filter Jenis - support both dropdown and text input
        if ($filterPerizinanJenis) {
            $queryPerizinan->where('jenis', 'like', "%{$filterPerizinanJenis}%");
        }
        
        // Global dropdown filter Jenis (from toolbar)
        if ($jenis && $jenis !== '') {
            $queryPerizinan->where('jenis', $jenis);
        }
        
        if ($filterPerizinanNoIzin) {
             $queryPerizinan->where('no_surat_keluar', 'like', "%{$filterPerizinanNoIzin}%");
        }
        
        // Filter Tanggal Terbit
        if ($filterPerizinanTglTerbit) {
            $queryPerizinan->whereDate('tanggal', $filterPerizinanTglTerbit);
        }
        
        // Filter Tanggal Akhir
        if ($filterPerizinanTglAkhir) {
            $queryPerizinan->whereDate('tanggal_akhir', $filterPerizinanTglAkhir);
        }
        
        // Filter Status - support both dropdown and text input
        if ($filterPerizinanStatus) {
            // Map status filter to status_izin calculation logic
            if (stripos($filterPerizinanStatus, 'aktif') !== false && stripos($filterPerizinanStatus, 'berakhir') === false) {
                // "Sedang Aktif" - tanggal_akhir > now
                $queryPerizinan->whereDate('tanggal_akhir', '>', now());
            } elseif (stripos($filterPerizinanStatus, 'mau') !== false || stripos($filterPerizinanStatus, 'akan') !== false) {
                // "Mau Berakhir" - tanggal_akhir between now and 30 days
                $queryPerizinan->whereDate('tanggal_akhir', '>', now())
                              ->whereDate('tanggal_akhir', '<=', now()->copy()->addDays(30));
            } elseif (stripos($filterPerizinanStatus, 'berakhir') !== false) {
                // "Berakhir" - tanggal_akhir < now
                $queryPerizinan->whereDate('tanggal_akhir', '<', now());
            }
        }
        
        // Global dropdown filter Status (from toolbar)
        if ($status && $status !== '') {
            if ($status === 'Sedang Aktif') {
                $queryPerizinan->whereDate('tanggal_akhir', '>', now());
            } elseif ($status === 'Mau Berakhir') {
                $queryPerizinan->whereDate('tanggal_akhir', '>', now())
                              ->whereDate('tanggal_akhir', '<=', now()->copy()->addDays(30));
            } elseif ($status === 'Berakhir') {
                $queryPerizinan->whereDate('tanggal_akhir', '<', now());
            }
        }
        
        if ($filterPerizinanKapasitas) {
            $queryPerizinan->where('total_kapasitas_kva', 'like', "%{$filterPerizinanKapasitas}%");
        }


        // Calculate Statistics for Perizinan Tab
        // We act on a clone of the base query (without pagination/ordering if possible, 
        // essentially all active perizinan, or filtered ones? Usually stats are for total data or filtered data.
        // Let's stick to ALL data for the cards, like rekap data, unless user wants filtered stats.
        // Re-using rekap data logic: Total Perizinan, IUPTLS, Rekomtek SKTP, Total Kapasitas
        
        // Simple counts from Perizinan table
        $now = now();
        $statsPerizinan = [
            'total_perizinan' => Perizinan::count(),
            'total_iuptls' => Perizinan::where('jenis', 'like', '%IUPTLS%')->count(),
            'total_rekomtek_sktp' => Perizinan::where(function($q) {
                $q->where('jenis', 'like', '%SKTP%') // Prioritize SKTP check if separated
                  ->orWhere('jenis', 'like', '%Rekomtek%');
            })->count(),
            'sedang_aktif' => Perizinan::whereDate('tanggal_akhir', '>', $now)->count(),
            'mau_berakhir' => Perizinan::whereDate('tanggal_akhir', '>', $now)
                                       ->whereDate('tanggal_akhir', '<=', $now->copy()->addDays(30))
                                       ->count(),
            'berakhir' => Perizinan::whereDate('tanggal_akhir', '<', $now)->count(),
        ];

        // Fetch distinct types of Perizinan for the filter dropdown
        $jenisIzinList = Perizinan::select('jenis')->distinct()->pluck('jenis');

        $perizinans = $queryPerizinan->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page_perizinan')
            ->withQueryString();

        return view('admin.permohonan.index', [
            'title' => 'Manajemen Permohonan',
            'permohonanUsers' => $permohonanUsers,
            'perizinans' => $perizinans,
            'regencies' => $regencies,
            'q' => $q,
            'tab' => $tab,
            'statsPerizinan' => $statsPerizinan,
            'jenisIzinList' => $jenisIzinList,
        ]);
    }

    /**
     * Show import form
     */
    public function import(Request $request)
    {
        return view('admin.permohonan.import', [
            'title' => 'Import Data Permohonan',
        ]);
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        return Excel::download(new PermohonanTemplateExport, 'template_import_permohonan.xlsx');
    }

    /**
     * Process import
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new PermohonanImport, $request->file('file'));
            return redirect()->route('admin.permohonan.index')->with('success', 'Data permohonan berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Get districts for cascading dropdown (AJAX)
     */
    public function optionsDistricts(Request $request)
    {
        $regencyId = $request->get('regency_id');
        $q = $request->get('q');

        $items = RegDistrict::when($regencyId, fn($qq) => $qq->where('regency_id', $regencyId))
            ->when($q, fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name']);

        return response()->json($items);
    }

    /**
     * Get villages for cascading dropdown (AJAX)
     */
    public function optionsVillages(Request $request)
    {
        $districtId = $request->get('district_id');
        $q = $request->get('q');

        $items = RegVillage::when($districtId, fn($qq) => $qq->where('district_id', $districtId))
            ->when($q, fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->limit(300)
            ->get(['id', 'name']);

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permohonan.create', [
            'title' => 'Tambah Permohonan',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_permohonan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'questions' => 'nullable|array',
            'questions.*.urutan' => 'required|integer|min:1',
            'questions.*.pertanyaan' => 'required|string',
            'questions.*.tipe' => 'required|string|in:text,textarea,number,date,file,radio,checkbox,select',
            'questions.*.wajib' => 'nullable|boolean',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*.opsi' => 'required|string',
            'questions.*.options.*.keterangan' => 'nullable|string',
            'questions.*.options.*.wajib' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Create permohonan
            $permohonan = Permohonan::create([
                'nama' => $validated['nama'],
                'jenis_permohonan' => $validated['jenis_permohonan'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Create questions
            if (isset($validated['questions']) && is_array($validated['questions'])) {
                foreach ($validated['questions'] as $questionData) {
                    $question = PermohonanQuestion::create([
                        'permohonan_id' => $permohonan->id,
                        'urutan' => $questionData['urutan'],
                        'pertanyaan' => $questionData['pertanyaan'],
                        'tipe' => $questionData['tipe'],
                        'wajib' => $questionData['wajib'] ?? false,
                    ]);

                    // Create options if tipe requires options (radio, checkbox, select)
                    if (
                        in_array($questionData['tipe'], ['radio', 'checkbox', 'select']) &&
                        isset($questionData['options']) && is_array($questionData['options'])
                    ) {
                        foreach ($questionData['options'] as $optionData) {
                            PermohonanQuestionOption::create([
                                'permohonan_question_id' => $question->id,
                                'opsi' => $optionData['opsi'],
                                'keterangan' => $optionData['keterangan'] ?? null,
                                'wajib' => $optionData['wajib'] ?? false,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.permohonan.index')
                ->with('success', 'Permohonan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permohonan $permohonan)
    {
        $permohonan->load([
            'questions.options' => function ($query) {
                $query->orderBy('id');
            }
        ]);
        $permohonan->questions = $permohonan->questions->sortBy('urutan')->values();

        // Prepare questions data for JavaScript
        $questionsData = $permohonan->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'urutan' => $q->urutan,
                'pertanyaan' => $q->pertanyaan,
                'tipe' => $q->tipe,
                'wajib' => $q->wajib,
                'options' => $q->options->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'opsi' => $opt->opsi,
                        'keterangan' => $opt->keterangan,
                        'wajib' => $opt->wajib,
                    ];
                })->toArray(),
            ];
        })->toArray();

        return view('admin.permohonan.edit', [
            'title' => 'Edit Permohonan',
            'permohonan' => $permohonan,
            'questionsData' => $questionsData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_permohonan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'questions' => 'nullable|array',
            'questions.*.id' => 'nullable|integer|exists:permohonan_questions,id',
            'questions.*.urutan' => 'required|integer|min:1',
            'questions.*.pertanyaan' => 'required|string',
            'questions.*.tipe' => 'required|string|in:text,textarea,number,date,file,radio,checkbox,select',
            'questions.*.wajib' => 'nullable|boolean',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*.id' => 'nullable|integer|exists:permohonan_question_options,id',
            'questions.*.options.*.opsi' => 'required|string',
            'questions.*.options.*.keterangan' => 'nullable|string',
            'questions.*.options.*.wajib' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update permohonan
            $permohonan->update([
                'nama' => $validated['nama'],
                'jenis_permohonan' => $validated['jenis_permohonan'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Get existing question IDs
            $existingQuestionIds = [];
            if (isset($validated['questions']) && is_array($validated['questions'])) {
                foreach ($validated['questions'] as $questionData) {
                    if (isset($questionData['id'])) {
                        $existingQuestionIds[] = $questionData['id'];

                        // Update existing question
                        $question = PermohonanQuestion::find($questionData['id']);
                        if ($question) {
                            $question->update([
                                'urutan' => $questionData['urutan'],
                                'pertanyaan' => $questionData['pertanyaan'],
                                'tipe' => $questionData['tipe'],
                                'wajib' => $questionData['wajib'] ?? false,
                            ]);
                        }
                    } else {
                        // Create new question
                        $question = PermohonanQuestion::create([
                            'permohonan_id' => $permohonan->id,
                            'urutan' => $questionData['urutan'],
                            'pertanyaan' => $questionData['pertanyaan'],
                            'tipe' => $questionData['tipe'],
                            'wajib' => $questionData['wajib'] ?? false,
                        ]);
                        $existingQuestionIds[] = $question->id;
                    }

                    // Handle options
                    if (
                        in_array($questionData['tipe'], ['radio', 'checkbox', 'select']) &&
                        isset($questionData['options']) && is_array($questionData['options'])
                    ) {

                        $existingOptionIds = [];
                        foreach ($questionData['options'] as $optionData) {
                            if (isset($optionData['id'])) {
                                $existingOptionIds[] = $optionData['id'];

                                // Update existing option
                                $option = PermohonanQuestionOption::find($optionData['id']);
                                if ($option) {
                                    $option->update([
                                        'opsi' => $optionData['opsi'],
                                        'keterangan' => $optionData['keterangan'] ?? null,
                                        'wajib' => $optionData['wajib'] ?? false,
                                    ]);
                                }
                            } else {
                                // Create new option
                                $option = PermohonanQuestionOption::create([
                                    'permohonan_question_id' => $question->id,
                                    'opsi' => $optionData['opsi'],
                                    'keterangan' => $optionData['keterangan'] ?? null,
                                    'wajib' => $optionData['wajib'] ?? false,
                                ]);
                                $existingOptionIds[] = $option->id;
                            }
                        }

                        // Delete options that are not in the request
                        PermohonanQuestionOption::where('permohonan_question_id', $question->id)
                            ->whereNotIn('id', $existingOptionIds)
                            ->delete();
                    } else {
                        // Delete all options if tipe doesn't require options
                        PermohonanQuestionOption::where('permohonan_question_id', $question->id)->delete();
                    }
                }
            }

            // Delete questions that are not in the request
            PermohonanQuestion::where('permohonan_id', $permohonan->id)
                ->whereNotIn('id', $existingQuestionIds)
                ->delete();

            DB::commit();

            return redirect()->route('admin.permohonan.index')
                ->with('success', 'Permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permohonan $permohonan)
    {
        $permohonan->delete();

        return redirect()->route('admin.permohonan.index')
            ->with('success', 'Permohonan berhasil dihapus.');
    }
}

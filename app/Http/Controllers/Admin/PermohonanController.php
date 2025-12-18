<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\PermohonanQuestion;
use App\Models\PermohonanQuestionOption;
use App\Models\Perizinan;
use App\Models\PerizinanDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');

        $query = Permohonan::withCount('questions');

        // Search by name or jenis_permohonan
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('jenis_permohonan', 'like', '%' . $q . '%');
            });
        }

        $permohonans = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Data Perizinan dari database
        $perizinans = Perizinan::with(['perusahaan', 'documents'])
            ->orderBy('created_at', 'desc')
            ->get();

        $perizinanData = $perizinans->map(function ($perizinan) {
            // Ambil document terbaru berdasarkan tanggal_terbit atau created_at
            $document = $perizinan->documents
                ->sortByDesc(function ($doc) {
                    return $doc->tanggal_terbit ?? $doc->created_at;
                })
                ->first();

            // Format tanggal pengajuan
            $tanggalPengajuan = $perizinan->tanggal
                ? $perizinan->tanggal->translatedFormat('d F Y')
                : ($perizinan->created_at ? $perizinan->created_at->translatedFormat('d F Y') : '-');

            // Format tanggal terbit dari document
            $tanggalTerbit = $document && $document->tanggal_terbit
                ? $document->tanggal_terbit->translatedFormat('d F Y')
                : null;

            // Format tanggal berlaku dari document (tanggal_akhir)
            $tanggalBerlaku = $document && $document->tanggal_akhir
                ? $document->tanggal_akhir->translatedFormat('d F Y')
                : '-';

            // Tentukan status berdasarkan document (tanggal_akhir)
            $status = 'Menunggu Verifikasi';
            $expiredDays = null;
            $remainingDays = null;
            if ($document) {
                if ($document->tanggal_akhir) {
                    $now = now();
                    $tanggalAkhir = \Carbon\Carbon::parse($document->tanggal_akhir);
                    $diffDays = $now->diffInDays($tanggalAkhir, false);

                    if ($tanggalAkhir->isPast()) {
                        // Sudah kadaluarsa - hitung jumlah hari kadaluarsa
                        $status = 'Expired';
                        $expiredDays = abs($diffDays);
                    } elseif ($diffDays <= 30) {
                        // Tersisa <= 30 hari (warning)
                        $status = 'Akan Kadaluarsa';
                        $remainingDays = $diffDays;
                    } elseif ($document->tanggal_terbit) {
                        // Masih aktif dan tersisa > 30 hari
                        $status = 'Aktif';
                        $remainingDays = $diffDays;
                    } else {
                        // Ada tanggal akhir tapi belum ada tanggal terbit
                        $status = 'Menunggu Terbit';
                    }
                } elseif ($document->tanggal_terbit) {
                    // Ada tanggal terbit tapi tidak ada tanggal akhir
                    $status = 'Aktif';
                } else {
                    // Ada dokumen tapi belum ada tanggal terbit dan tanggal akhir
                    $status = 'Menunggu Terbit';
                }
            }

            // Sumber pengajuan (bisa dari catatan atau default)
            $sumberPengajuan = $perizinan->catatan ?: 'Dari Sistem';

            return [
                'id' => $perizinan->id,
                'perusahaan' => $perizinan->perusahaan->nama ?? '-',
                'jenis_izin' => $perizinan->jenis ?? '-',
                'tanggal_berlaku' => $tanggalBerlaku,
                'sumber_pengajuan' => $sumberPengajuan,
                'tanggal_pengajuan' => $tanggalPengajuan,
                'tanggal_terbit' => $tanggalTerbit,
                'status' => $status,
                'expired_days' => $expiredDays,
                'remaining_days' => $remainingDays,
            ];
        })->toArray();

        // Data Permohonan dari PermohonanUser
        $permohonanUsers = PermohonanUser::with(['permohonan', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $permohonanData = $permohonanUsers->map(function ($permohonanUser) {
            $statusText = ucfirst($permohonanUser->status);

            // Mapping status ke format yang lebih user-friendly
            $statusMap = [
                'pending' => 'Menunggu Verifikasi',
                'diproses' => 'Sedang Diproses',
                'selesai' => 'Aktif',
                'ditolak' => 'Ditolak',
                'expired' => 'Expired',
            ];

            $statusText = $statusMap[$permohonanUser->status] ?? $statusText;

            // Format tanggal
            $tanggalPengajuan = $permohonanUser->created_at
                ? $permohonanUser->created_at->translatedFormat('d F Y')
                : '-';

            $tanggalTerbit = null;
            if ($permohonanUser->status === 'selesai' && $permohonanUser->updated_at) {
                $tanggalTerbit = $permohonanUser->updated_at->translatedFormat('d F Y');
            }

            // Tanggal berlaku (default ke 1 tahun dari tanggal pengajuan atau updated_at jika selesai)
            $tanggalBerlaku = '-';
            if ($permohonanUser->status === 'selesai' && $permohonanUser->updated_at) {
                $tanggalBerlaku = $permohonanUser->updated_at->copy()->addYear()->translatedFormat('d F Y');
            } elseif ($permohonanUser->created_at) {
                $tanggalBerlaku = $permohonanUser->created_at->copy()->addYear()->translatedFormat('d F Y');
            }

            return [
                'id' => $permohonanUser->id,
                'permohonan_id' => $permohonanUser->permohonan_id,
                'perusahaan' => $permohonanUser->user->name ?? '-',
                'jenis_izin' => $permohonanUser->permohonan->nama ?? '-',
                'tanggal_berlaku' => $tanggalBerlaku,
                'sumber_pengajuan' => 'Dari Sistem',
                'tanggal_pengajuan' => $tanggalPengajuan,
                'tanggal_terbit' => $tanggalTerbit,
                'status' => $statusText,
            ];
        })->toArray();

        return view('admin.permohonan.index', [
            'title' => 'Perizinan dan Permohonan',
            'permohonans' => $permohonans,
            'perizinanData' => $perizinanData,
            'permohonanData' => $permohonanData,
        ]);
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

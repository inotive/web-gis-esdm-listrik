<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\PermohonanQuestion;
use App\Models\PermohonanQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriPermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $kind = $request->get('kind'); // Filter jenis permohonan

        $query = Permohonan::withCount('questions');

        // Search by name or jenis_permohonan
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('jenis_permohonan', 'like', '%' . $q . '%');
            });
        }

        // Filter by jenis_permohonan
        if ($kind) {
            $query->where('jenis_permohonan', $kind);
        }

        $permohonans = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Get unique kinds for filter dropdown
        $kinds = Permohonan::distinct()->pluck('jenis_permohonan')->filter()->values();

        return view('admin.kategori-permohonan.index', [
            'title' => 'Kategori Permohonan',
            'permohonans' => $permohonans,
            'kinds' => $kinds
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori-permohonan.create', [
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

            return redirect()->route('admin.kategori-permohonan.index')
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

        return view('admin.kategori-permohonan.edit', [
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

            return redirect()->route('admin.kategori-permohonan.index')
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

        return redirect()->route('admin.kategori-permohonan.index')
            ->with('success', 'Permohonan berhasil dihapus.');
    }
}

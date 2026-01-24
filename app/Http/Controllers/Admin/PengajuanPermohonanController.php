<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\PermohonanUserDocument;
use App\Models\Dokumen;
use App\Helpers\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanPermohonanController extends Controller
{
    use UploadFile;

    /**
     * Display a listing of user's permohonan submissions
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $status = $request->get('status');
        $permohonanId = $request->get('permohonan_id');

        // PENTING: Hanya ambil permohonan milik user yang sedang login
        $query = Auth::user()->permohonanUsers()->with('permohonan');

        // Filter by status
        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        // Filter by permohonan type
        if ($permohonanId) {
            $query->where('permohonan_id', $permohonanId);
        }

        // Search by permohonan name or keterangan
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->whereHas('permohonan', function($query) use ($q) {
                    $query->where('nama', 'like', '%' . $q . '%');
                })->orWhere('keterangan', 'like', '%' . $q . '%');
            });
        }

        $permohonanUsers = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Get available permohonan types for filter
        $userRole = Auth::user()->roles()->first()->name ?? null;
        $permohonans = Permohonan::where('jenis_permohonan', $userRole)
            ->orderBy('nama')
            ->get();

        // Count by status for tabs
        $statusCounts = [
            'semua' => Auth::user()->permohonanUsers()->count(),
            'pending' => Auth::user()->permohonanUsers()->where('status', 'pending')->count(),
            'proses' => Auth::user()->permohonanUsers()->where('status', 'proses')->count(),
            'selesai' => Auth::user()->permohonanUsers()->where('status', 'selesai')->count(),
            'ditolak' => Auth::user()->permohonanUsers()->where('status', 'ditolak')->count(),
        ];

        return view('admin.pengajuan-permohonan.index', [
            'title' => 'Pengajuan Permohonan Saya',
            'permohonanUsers' => $permohonanUsers,
            'permohonans' => $permohonans,
            'statusCounts' => $statusCounts,
            'currentStatus' => $status ?? 'semua',
            'currentPermohonanId' => $permohonanId,
        ]);
    }

    /**
     * Show page to select permohonan type
     */
    public function selectType()
    {
        $userRole = Auth::user()->roles()->first()->name ?? null;

        // Get available permohonan types for this user role
        $permohonans = Permohonan::where('jenis_permohonan', $userRole)
            ->withCount('questions')
            ->orderBy('nama')
            ->get();

        return view('admin.pengajuan-permohonan.select-type', [
            'title' => 'Pilih Jenis Permohonan',
            'permohonans' => $permohonans,
        ]);
    }

    /**
     * Show the form for creating a new permohonan submission
     */
    public function create(Request $request)
    {
        $permohonanId = $request->get('permohonan_id');

        if (!$permohonanId) {
            return redirect()->route('admin.pengajuan-permohonan.select-type')
                ->withErrors(['error' => 'Silakan pilih jenis permohonan terlebih dahulu.']);
        }

        $userRole = Auth::user()->roles()->first()->name ?? null;

        // Validate permohonan belongs to user's role
        $permohonan = Permohonan::with(['questions.options' => function($query) {
            $query->orderBy('id');
        }])->where('id', $permohonanId)
            ->where('jenis_permohonan', $userRole)
            ->firstOrFail();

        // Sort questions by urutan
        $permohonan->questions = $permohonan->questions->sortBy('urutan')->values();

        return view('admin.pengajuan-permohonan.create', [
            'title' => 'Buat Permohonan: ' . $permohonan->nama,
            'permohonan' => $permohonan,
        ]);
    }

    /**
     * Store a newly created permohonan submission
     */
    public function store(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Store Permohonan - Request Data:', [
            'all' => $request->all(),
            'permohonan_id' => $request->input('permohonan_id'),
            'jawaban' => $request->input('jawaban'),
            'user_id' => Auth::id(),
        ]);

        $validated = $request->validate([
            'permohonan_id' => 'required|exists:permohonans,id',
            'jawaban' => 'nullable|array',
            'keterangan' => 'nullable|string',
        ]);

        \Log::info('Store Permohonan - Validated Data:', $validated);

        // Validate permohonan belongs to user's role
        $userRole = Auth::user()->roles()->first()->name ?? null;

        \Log::info('Store Permohonan - User Role:', ['role' => $userRole]);

        $permohonan = Permohonan::with('questions.options')
            ->where('id', $validated['permohonan_id'])
            ->where('jenis_permohonan', $userRole)
            ->firstOrFail();

        \Log::info('Store Permohonan - Permohonan Found:', [
            'id' => $permohonan->id,
            'nama' => $permohonan->nama,
            'questions_count' => $permohonan->questions->count(),
        ]);

        // Validate required questions
        $requiredQuestions = $permohonan->questions->where('wajib', true);
        $jawaban = $validated['jawaban'] ?? [];

        \Log::info('Store Permohonan - Required Questions:', [
            'count' => $requiredQuestions->count(),
            'jawaban_count' => count($jawaban),
        ]);

        foreach ($requiredQuestions as $question) {
            if (!isset($jawaban[$question->id]) || empty($jawaban[$question->id])) {
                \Log::warning('Store Permohonan - Missing Required Answer:', [
                    'question_id' => $question->id,
                    'question' => $question->pertanyaan,
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['jawaban.' . $question->id => 'Pertanyaan "' . $question->pertanyaan . '" wajib diisi.']);
            }
        }

        // Process checkbox answers (convert array values to integers)
        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                $jawaban[$key] = array_map('intval', $value);
            } elseif (is_numeric($value)) {
                $jawaban[$key] = (int) $value;
            }
        }

        \Log::info('Store Permohonan - Processed Jawaban:', $jawaban);

        DB::beginTransaction();
        try {
            $dataToInsert = [
                'permohonan_id' => $validated['permohonan_id'],
                'user_id' => Auth::id(),
                'status' => 'pending',
                'jawaban' => $jawaban,
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            \Log::info('Store Permohonan - Data to Insert:', $dataToInsert);

            $permohonanUser = PermohonanUser::create($dataToInsert);

            \Log::info('Store Permohonan - Created Successfully:', [
                'id' => $permohonanUser->id,
            ]);

            DB::commit();

            return redirect()->route('admin.pengajuan-permohonan.index')
                ->with('success', 'Permohonan berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Store Permohonan - Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified permohonan submission
     */
    public function show(PermohonanUser $pengajuanPermohonan)
    {
        // Ensure user can only see their own permohonan
        if ($pengajuanPermohonan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pengajuanPermohonan->load([
            'permohonan.questions.options' => function($query) {
                $query->orderBy('id');
            },
            'documents.dokumen',
            'user',
            'approver'
        ]);

        $pengajuanPermohonan->permohonan->questions = $pengajuanPermohonan->permohonan->questions->sortBy('urutan')->values();

        $jawaban = $pengajuanPermohonan->jawaban ?? [];

        return view('admin.pengajuan-permohonan.show', [
            'title' => 'Detail Permohonan',
            'permohonanUser' => $pengajuanPermohonan,
            'jawaban' => $jawaban,
        ]);
    }

    /**
     * Show the form for editing the specified permohonan submission
     */
    public function edit(PermohonanUser $pengajuanPermohonan)
    {
        // Ensure user can only edit their own permohonan
        if ($pengajuanPermohonan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow edit if status is pending
        if ($pengajuanPermohonan->status !== 'pending') {
            return redirect()->route('admin.pengajuan-permohonan.index')
                ->withErrors(['error' => 'Hanya permohonan dengan status Pending yang dapat diedit.']);
        }

        $pengajuanPermohonan->load(['permohonan.questions.options' => function($query) {
            $query->orderBy('id');
        }]);

        $pengajuanPermohonan->permohonan->questions = $pengajuanPermohonan->permohonan->questions->sortBy('urutan')->values();

        $jawaban = $pengajuanPermohonan->jawaban ?? [];

        // Convert option IDs to strings for comparison in view
        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                $jawaban[$key] = array_map('strval', $value);
            } else {
                $jawaban[$key] = (string) $value;
            }
        }

        return view('admin.pengajuan-permohonan.edit', [
            'title' => 'Edit Permohonan',
            'permohonanUser' => $pengajuanPermohonan,
            'jawaban' => $jawaban,
        ]);
    }

    /**
     * Update the specified permohonan submission
     */
    public function update(Request $request, PermohonanUser $pengajuanPermohonan)
    {
        // Ensure user can only update their own permohonan
        if ($pengajuanPermohonan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow update if status is pending
        if ($pengajuanPermohonan->status !== 'pending') {
            return redirect()->route('admin.pengajuan-permohonan.index')
                ->withErrors(['error' => 'Hanya permohonan dengan status Pending yang dapat diedit.']);
        }

        $validated = $request->validate([
            'jawaban' => 'nullable|array',
            'keterangan' => 'nullable|string',
        ]);

        // Get permohonan to validate questions
        $permohonan = $pengajuanPermohonan->permohonan;
        $permohonan->load('questions.options');

        // Validate required questions
        $requiredQuestions = $permohonan->questions->where('wajib', true);
        $jawaban = $validated['jawaban'] ?? [];

        foreach ($requiredQuestions as $question) {
            if (!isset($jawaban[$question->id]) || empty($jawaban[$question->id])) {
                return back()
                    ->withInput()
                    ->withErrors(['jawaban.' . $question->id => 'Pertanyaan "' . $question->pertanyaan . '" wajib diisi.']);
            }
        }

        // Process checkbox answers (convert array values to integers)
        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                $jawaban[$key] = array_map('intval', $value);
            } elseif (is_numeric($value)) {
                $jawaban[$key] = (int) $value;
            }
        }

        DB::beginTransaction();
        try {
            $pengajuanPermohonan->update([
                'jawaban' => $jawaban,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.pengajuan-permohonan.index')
                ->with('success', 'Permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel permohonan (delete if status is pending)
     */
    public function destroy(PermohonanUser $pengajuanPermohonan)
    {
        // Ensure user can only delete their own permohonan
        if ($pengajuanPermohonan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only can cancel if status is pending
        if ($pengajuanPermohonan->status !== 'pending') {
            return redirect()->route('admin.pengajuan-permohonan.index')
                ->withErrors(['error' => 'Hanya permohonan dengan status Pending yang dapat dibatalkan.']);
        }

        $pengajuanPermohonan->delete();

        return redirect()->route('admin.pengajuan-permohonan.index')
            ->with('success', 'Permohonan berhasil dibatalkan.');
    }
}

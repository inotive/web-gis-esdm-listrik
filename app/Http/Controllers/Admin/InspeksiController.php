<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inspeksi;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Jobs\ProcessNewInspeksiNotification;
use App\Jobs\ProcessInspeksiFeedbackNotification;
use App\Jobs\ProcessInspeksiStatusNotification;

class InspeksiController extends Controller
{
    /**
     * Display a listing of inspections.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q'); // Global search
        $perusahaanId = $request->get('perusahaan_id');
        $tanggalDari = $request->get('tanggal_dari');
        $tanggalSampai = $request->get('tanggal_sampai');

        // Authorization
        // abort_unless(Auth::user()->can('inspeksi.view'), 403, 'Unauthorized');

        $query = Inspeksi::with(['perusahaan', 'pengguna']);

        // Global search
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('referensi_izin', 'like', '%' . $q . '%')
                    ->orWhere('catatan', 'like', '%' . $q . '%')
                    ->orWhereHas('perusahaan', function ($subQuery) use ($q) {
                        $subQuery->where('nama', 'like', '%' . $q . '%');
                    });
            });
        }

        // Filter by perusahaan
        if ($perusahaanId) {
            $query->where('perusahaan_id', $perusahaanId);
        }

        // Filter for logged-in company user
        if (Auth::user()->hasRole('perusahaan') && Auth::user()->perusahaan_id) {
            $query->where('perusahaan_id', Auth::user()->perusahaan_id);
        }

        // Filter by date range
        if ($tanggalDari) {
            $query->whereDate('tanggal', '>=', $tanggalDari);
        }
        if ($tanggalSampai) {
            $query->whereDate('tanggal', '<=', $tanggalSampai);
        }

        $inspeksis = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // For filter dropdown
        $perusahaans = Perusahaan::orderBy('nama')->get(['id', 'nama']);

        return view('admin.inspeksi.index', [
            'title' => 'Manajemen Inspeksi',
            'inspeksis' => $inspeksis,
            'perusahaans' => $perusahaans,
        ]);
    }

    /**
     * Show the form for creating a new inspection.
     */
    public function create()
    {
        abort_unless(Auth::user()->can('inspeksi.create'), 403, 'Unauthorized');

        $perusahaans = Perusahaan::orderBy('nama')->get(['id', 'nama']);

        return view('admin.inspeksi.create', [
            'title' => 'Tambah Inspeksi',
            'perusahaans' => $perusahaans,
        ]);
    }

    /**
     * Store a newly created inspection in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('inspeksi.create'), 403, 'Unauthorized');

        $data = $this->validatedData($request);

        // Set ditambahkan_oleh to current user
        $data['ditambahkan_oleh'] = Auth::id();

        // Handle lampiran file upload
        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('inspeksi/lampiran', 'public');
        }

        $inspeksi = Inspeksi::create($data);

        // Dispatch notification job
        ProcessNewInspeksiNotification::dispatch($inspeksi->id);

        return redirect()->route('admin.inspeksi.index')->with('success', 'Data inspeksi berhasil ditambahkan.');
    }

    /**
     * Display the specified inspection.
     */
    public function show(Inspeksi $inspeksi)
    {
        // abort_unless(Auth::user()->can('inspeksi.view'), 403, 'Unauthorized');

        $inspeksi->load(['perusahaan', 'pengguna', 'feedback']);

        return view('admin.inspeksi.show', [
            'title' => 'Detail Inspeksi',
            'inspeksi' => $inspeksi,
        ]);
    }

    /**
     * Show the form for editing the specified inspection.
     */
    public function edit(Inspeksi $inspeksi)
    {
        abort_unless(Auth::user()->can('inspeksi.edit'), 403, 'Unauthorized');

        $inspeksi->load(['perusahaan', 'pengguna']);
        $perusahaans = Perusahaan::orderBy('nama')->get(['id', 'nama']);

        return view('admin.inspeksi.edit', [
            'title' => 'Edit Inspeksi',
            'inspeksi' => $inspeksi,
            'perusahaans' => $perusahaans,
        ]);
    }

    /**
     * Update the specified inspection in storage.
     */
    public function update(Request $request, Inspeksi $inspeksi)
    {
        abort_unless(Auth::user()->can('inspeksi.edit'), 403, 'Unauthorized');

        $data = $this->validatedData($request);

        // Handle lampiran file upload
        if ($request->hasFile('lampiran')) {
            // Delete old file if exists
            if ($inspeksi->lampiran && Storage::disk('public')->exists($inspeksi->lampiran)) {
                Storage::disk('public')->delete($inspeksi->lampiran);
            }
            $data['lampiran'] = $request->file('lampiran')->store('inspeksi/lampiran', 'public');
        }

        $oldStatus = $inspeksi->status;
        $inspeksi->update($data);
        $newStatus = $inspeksi->status;

        if ($oldStatus !== $newStatus) {
            ProcessInspeksiStatusNotification::dispatch($inspeksi->id, $oldStatus, $newStatus);
        }

        return redirect()->route('admin.inspeksi.index')->with('success', 'Data inspeksi berhasil diperbarui.');
    }

    /**
     * Remove the specified inspection from storage.
     */
    public function destroy(Inspeksi $inspeksi)
    {
        abort_unless(Auth::user()->can('inspeksi.delete'), 403, 'Unauthorized');

        // Delete associated file (lampiran)
        if ($inspeksi->lampiran && Storage::disk('public')->exists($inspeksi->lampiran)) {
            Storage::disk('public')->delete($inspeksi->lampiran);
        }

        $inspeksi->delete();

        return redirect()->route('admin.inspeksi.index')->with('success', 'Data inspeksi berhasil dihapus.');
    }

    /**
     * Store feedback for inspection
     */
    public function storeFeedback(Request $request, Inspeksi $inspeksi)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'posisi' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:255'],
            'catatan' => ['required', 'string'],
            'file_upload' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
        ]);

        // Authorization check
        if (Auth::user()->hasRole('perusahaan') && Auth::user()->perusahaan_id !== $inspeksi->perusahaan_id) {
            abort(403);
        }

        $filePath = null;
        if ($request->hasFile('file_upload')) {
            $filePath = $request->file('file_upload')->store('inspeksi/feedback', 'public');
        }

        $inspeksi->feedback()->create([
            'nama' => $request->nama,
            'posisi' => $request->posisi,
            'kontak' => $request->kontak,
            'catatan' => $request->catatan,
            'file_upload' => $filePath,
        ]);

        // Dispatch notification job
        ProcessInspeksiFeedbackNotification::dispatch($inspeksi->id);

        return back()->with('success', 'Feedback berhasil dikirim.');
    }

    /**
     * Validate inspection data
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'referensi_izin' => ['required', 'string', 'max:255'],
            'perusahaan_id' => ['required', 'exists:perusahaans,id'],
            'berita_acara' => ['nullable', 'string'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'], // 5MB max
            'catatan' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'max:50'],
        ], [], [
            'tanggal' => 'Tanggal',
            'referensi_izin' => 'Referensi Izin',
            'perusahaan_id' => 'Perusahaan',
            'berita_acara' => 'Berita Acara',
            'lampiran' => 'Lampiran',
            'catatan' => 'Catatan',
        ]);
    }
}

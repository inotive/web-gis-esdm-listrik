<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jabatan::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_jabatan', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhere('jabatan', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('no_telp', 'like', '%' . $search . '%');
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $jabatans = $query->latest()->paginate(10)->withQueryString();

        return view('admin.jabatan.index', [
            'title' => 'Data jabatan',
            'jabatans' => $jabatans,
            'statusOptions' => ['aktif', 'nonaktif'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['required', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat_kantor' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Jabatan::create($validated);

        return redirect()->route('admin.jabatan.index')->with([
            'success' => 'Berhasil menambahkan data jabatan.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['required', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat_kantor' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $jabatan->update($validated);

        return redirect()->route('admin.jabatan.index')->with([
            'success' => 'Berhasil memperbarui data jabatan.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')->with([
            'success' => 'Berhasil menghapus data jabatan.',
        ]);
    }
}

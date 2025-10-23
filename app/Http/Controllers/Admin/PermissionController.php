<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [
            'title' => 'Manajemen Permission',
            'data' => Permission::latest()->get(),
        ];

        return view('admin.permission.index', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $notifikasi = [
                'pesan' => $validator->errors()->first(),
                'alert' => 'error',
            ];

            return redirect()->back()->withInput()->with($notifikasi);
        }

        $permissionExists = Permission::where('name', $request->name)->first();

        if ($permissionExists !== null) {
            $notifikasi = [
                'pesan' => 'Permission Sudah Ada!',
                'alert' => 'error',
            ];

            return redirect()->back()->withInput()->with($notifikasi);
        }

        Permission::create([
            'group' => $request->group,
            'display_name' => $request->display_name,
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $notifikasi = [
            'pesan' => 'Berhasil Tambah Data Permission!',
            'alert' => 'success',
        ];

        return redirect()->route('admin.hak-akses.permission.index')->with($notifikasi);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $notifikasi = [
                'pesan' => $validator->errors()->first(),
                'alert' => 'error',
            ];

            return redirect()->back()->withInput()->with($notifikasi);
        }

        $permissionExists = Permission::where('name', $request->name)
            ->where('id', '!=', $permission->id)
            ->first();

        if ($permissionExists !== null) {
            $notifikasi = [
                'pesan' => 'Nama Permission Telah Terpakai!',
                'alert' => 'error',
            ];

            return redirect()->back()->withInput()->with($notifikasi);
        }

        $permission->update([
            'group' => $request->group,
            'display_name' => $request->display_name,
            'name' => $request->name,
        ]);

        $notifikasi = [
            'pesan' => 'Berhasil Ubah Data Permission!',
            'alert' => 'success',
        ];

        return redirect()->route('admin.hak-akses.permission.index')->with($notifikasi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data Permission!',
            'alert' => 'success',
        ];

        return redirect()->route('admin.hak-akses.permission.index')->with($notifikasi);
    }
}

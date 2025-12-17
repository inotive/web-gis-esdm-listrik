<?php
// app/Http/Controllers/Admin/RoleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        abort_unless(auth()->user()->can('role.view'), 403, 'Anda tidak memiliki akses ke halaman ini.');

        $roles = SpatieRole::query()
            ->when(method_exists(SpatieRole::class, 'users'), function (Builder $query) {
                return $query->withCount(['users']);
            })
            // hitung total permission per role (berdasarkan pivot), filter display_name jika ada
            ->select('roles.*')
            ->selectSub(function ($q) {
                $q->from('role_has_permissions as rhp')
                    ->join('permissions as p', 'p.id', '=', 'rhp.permission_id')
                    ->whereColumn('rhp.role_id', 'roles.id')
                    ->whereNotNull('p.display_name')
                    ->where('p.display_name', '!=', '')
                    ->selectRaw('COUNT(*)');
            }, 'permissions_total')
            // fallback: total tanpa filter
            ->selectSub(function ($q) {
                $q->from('role_has_permissions as rhp')
                    ->whereColumn('rhp.role_id', 'roles.id')
                    ->selectRaw('COUNT(*)');
            }, 'permissions_count')
            ->orderBy('name')
            ->get();

        return view('admin.role.index', [
            'title' => 'Manajemen Role',
            'data' => $roles,
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->can('role.create'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('roles', 'name')],
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah digunakan.',
        ]);

        $role = SpatieRole::create([
            'name' => $validated['name'],
            'guard_name' => config('auth.defaults.guard', 'web'),
        ]);

        $notification = [
            'pesan' => "Role {$role->name} berhasil ditambahkan!",
            'alert' => 'success',
        ];

        return redirect()
            ->route('admin.hak-akses.role.index')
            ->with($notification);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, SpatieRole $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('role.edit'), 403);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah digunakan.',
        ]);

        $role->update(['name' => $validated['name']]);

        $notification = [
            'pesan' => 'Role berhasil diperbarui!',
            'alert' => 'success',
        ];

        return redirect()
            ->route('admin.hak-akses.role.index')
            ->with($notification);
    }

    /**
     * Remove the specified role.
     */
    public function destroy(SpatieRole $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('role.delete'), 403);

        if (strtolower($role->name) === 'super admin') {
            $notification = [
                'pesan' => 'Role Super Admin tidak dapat dihapus!',
                'alert' => 'error',
            ];

            return redirect()
                ->route('admin.hak-akses.role.index')
                ->with($notification);
        }

        if (method_exists($role, 'users') && $role->users()->exists()) {
            $notification = [
                'pesan' => 'Role masih digunakan oleh pengguna lain.',
                'alert' => 'error',
            ];

            return redirect()
                ->route('admin.hak-akses.role.index')
                ->with($notification);
        }

        $roleName = $role->name;
        $role->delete();

        $notification = [
            'pesan' => "Role {$roleName} berhasil dihapus!",
            'alert' => 'success',
        ];

        return redirect()
            ->route('admin.hak-akses.role.index')
            ->with($notification);
    }

    /**
     * Show permission management page for a role.
     */
    public function permissions(SpatieRole $role): View
    {
        abort_unless(auth()->user()->can('role.permission'), 403, 'Anda tidak memiliki akses untuk mengelola permission.');

        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.role.permissions', [
            'title' => 'Kelola Permission - ' . $role->name,
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Update permissions for a role.
     * ✅ FIXED: Menggunakan permission names, bukan IDs
     */
    public function updatePermissions(Request $request, SpatieRole $role): RedirectResponse
    {
        abort_unless(auth()->user()->can('role.permission'), 403);

        try {
            // Validasi input
            $validated = $request->validate([
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['integer', 'exists:permissions,id'],
            ], [
                'permissions.array' => 'Format permission tidak valid.',
                'permissions.*.integer' => 'ID permission harus berupa angka.',
                'permissions.*.exists' => 'Permission dengan ID tidak ditemukan di database.',
            ]);

            $permissionIds = $validated['permissions'] ?? [];

            Log::info('Update Permissions Started', [
                'role' => $role->name,
                'permission_ids_received' => $permissionIds,
                'count' => count($permissionIds),
            ]);

            // ✅ CRITICAL FIX: Ambil Permission objects dan gunakan name untuk sync
            if (empty($permissionIds)) {
                // Jika tidak ada permission yang dipilih, hapus semua
                $role->syncPermissions([]);
                Log::info('All permissions removed from role', ['role' => $role->name]);
            } else {
                // Ambil Permission objects berdasarkan IDs yang valid
                $permissions = Permission::whereIn('id', $permissionIds)
                    ->where('guard_name', 'web')
                    ->get();

                Log::info('Permissions found', [
                    'requested_ids' => $permissionIds,
                    'found_count' => $permissions->count(),
                    'found_names' => $permissions->pluck('name')->toArray(),
                ]);

                // ✅ CRITICAL: Sync menggunakan permission NAMES, bukan IDs
                $role->syncPermissions($permissions->pluck('name')->toArray());
            }

            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Reload role untuk mendapatkan data terbaru
            $role->load('permissions');

            Log::info('Permissions synced successfully', [
                'role' => $role->name,
                'final_count' => $role->permissions->count(),
            ]);

            $notification = [
                'pesan' => "Permission untuk role {$role->name} berhasil diperbarui! ({$role->permissions->count()} permissions)",
                'alert' => 'success',
            ];

            return redirect()
                ->route('admin.hak-akses.role.index')
                ->with($notification);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error', [
                'role' => $role->name,
                'errors' => $e->errors(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with([
                    'pesan' => 'Validasi gagal. Periksa data yang Anda kirim.',
                    'alert' => 'error',
                ]);

        } catch (\Exception $e) {
            Log::error('Error Updating Permissions', [
                'role' => $role->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'alert' => 'error',
                ]);
        }
    }
}
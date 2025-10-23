<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
	public function index(): View
	{
		$roles = SpatieRole::query()
			->when(method_exists(SpatieRole::class, 'users'), function (Builder $query) {
				return $query->withCount(['users']);
			})
			->orderBy('name')
			->get();

		return view('admin.role.index', [
			'title' => 'Manajemen Role',
			'data' => $roles,
		]);
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:150', Rule::unique('roles', 'name')],
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

	public function update(Request $request, SpatieRole $role): RedirectResponse
	{
		$validated = $request->validate([
			'name' => [
				'required',
				'string',
				'max:150',
				Rule::unique('roles', 'name')->ignore($role->id),
			],
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

	public function destroy(SpatieRole $role): RedirectResponse
	{
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
}


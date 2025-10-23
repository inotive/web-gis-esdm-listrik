<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\UploadFile;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use UploadFile;

    public function index()
    {
        $db = User::latest()->get();

        $view = [
            'title' => "Manajemen Pengguna",
            'data' => $db,
            'role' => Role::all()
        ];

        return view('admin.user.index', $view);
    }

    public function create()
    {
        $db = User::latest()->get();

        $view = [
            'title' => "Tambah Pengguna",
            'data' => $db,
            'role' => Role::all()
        ];

        return view('admin.user.create', $view);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|alpha_dash|unique:users,username',
            'name'     => 'required',
            'password' => 'required|min:6',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'email.unique'    => 'Email sudah terdaftar.',
            'username.unique' => 'Username telah terpakai.',
            'password.min'    => 'Password minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            $notifikasi = [
                'pesan' => $validator->errors()->first(), // ambil pesan pertama agar muncul di toastr
                'alert' => "error"
            ];
            return redirect()->back()->withInput()->with($notifikasi);
        }

        // (Opsional) cek role kosong di-backend (UI default = Admin)
        if ($request->role == null) {
            $notifikasi = [
                'pesan' => "Role Tidak Boleh Kosong!",
                'alert' => "error"
            ];
            return redirect()->back()->withInput()->with($notifikasi);
        }

        $data = [
            'email'    => $request->email,
            'username' => $request->username,
            'name'     => $request->name,
            'status'   => "aktif",
            'password' => bcrypt($request->password)
        ];

        if ($request->file('image') !== null) {
            $gambar = $this->storeFile($request->file('image'), 'profile');
            $data['image'] = $gambar;
        }

        $user = User::create($data);
        $user->syncRoles([$request->role]);

        $notifikasi = [
            'pesan' => "Berhasil Tambah Data!",
            'alert' => "success"
        ];
        return redirect()->route('admin.hak-akses.user.index')->with($notifikasi);
    }

    public function show(string $id) { /* ... */ }

    public function edit(string $id) { /* ... */ }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|alpha_dash|unique:users,username,' . $user->id,
            'name'     => 'required',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'email.unique'    => 'Email sudah terdaftar.',
            'username.unique' => 'Username telah terpakai.',
        ]);

        if ($validator->fails()) {
            $notifikasi = [
                'pesan' => $validator->errors()->first(),
                'alert' => "error"
            ];
            return redirect()->back()->withInput()->with($notifikasi);
        }

        if (strlen($request->password ?? '') > 0 && strlen($request->password) < 6) {
            $notifikasi = [
                'pesan' => 'Password Harus Minimal 6 Karakter!',
                'alert' => "error"
            ];
            return redirect()->back()->withInput()->with($notifikasi);
        }

        $imageProfile = $user->image;

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete('profile/' . $user->image);
            }
            $image = $this->storeFile($request->file('image'), 'profile');
            $imageProfile = $image;
        }

        $data = [
            'username' => $request->username,
            'email'    => $request->email,
            'name'     => $request->name,
            'status'   => 'aktif',
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $data['image'] = $imageProfile;

        $user->fill($data)->update();
        $user->syncRoles($request->role);

        $notifikasi = [
            'pesan' => 'Berhasil Ubah Data!',
            'alert' => "success"
        ];
        return redirect()->route('admin.hak-akses.user.index')->with($notifikasi);
    }

    public function destroy(User $user){
        if ($user->image) {
            Storage::disk('public')->delete('profile/' . $user->image);
        }
        $user->delete();

        $notifikasi = [
            'pesan' => 'Berhasil Hapus Data!',
            'alert' => "success"
        ];
        return redirect()->route('admin.hak-akses.user.index')->with($notifikasi);
    }
}

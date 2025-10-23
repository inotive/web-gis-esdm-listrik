<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UploadFile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use UploadFile;
    public function profile(User $profile){
        $view = ['title' => "Profile", 
        'data' => $profile,
        'role' => Role::all()];
        return view('admin.profile.index', $view);
    }
    public function updateProfile(Request $request,User $profile){
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'username' => 'required',
                'name' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);
     
            if ($validator->fails()) {
                $notifikasi = array(
                    'pesan' => $validator->errors(),
                    'alert' => "error"
                );
                return redirect()->back()->with($notifikasi);
            }
    
            $usernameTersedia = User::where('username', $request->username)->where('id', '!=', $profile->id)->first();
              
            if ($usernameTersedia !== null) {
                $notifikasi = array(
                    'pesan' => "Username Telah Terpakai!",
                    'alert' => "error"
                );
                return redirect()->back()->with($notifikasi);
            }
    
            $imageProfile = $profile->image; 
    
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete('profile/' . $profile->image); 
                $image = $this->storeFile($request->file('image'), 'profile');
                $imageProfile = $image;
            }
    
            $data = [
                'username' => $request->username,
                'email' => $request->email,
                'name' => $request->name,
                'status' => 'aktif',
            ];
            
            if ($request->filled('password')) {
                if (strlen($request->password) < 6) {
                    $notifikasi = array(
                        'pesan' => 'Password Harus Minimal 6 Karakter!',
                        'alert' => "error"
                    );
                    return redirect()->back()->with($notifikasi);
                }
            
                $data['password'] = bcrypt($request->password);
            }
            
            $data['image'] = $imageProfile; 
            $profile->fill($data);
            $profile->update();
            $notifikasi = array(
                'pesan' => 'Berhasil Ubah Profile!',
                'alert' => "success"
            );
            return redirect()->route('admin.profile.index', $profile->id)->with($notifikasi);
     
    }
}

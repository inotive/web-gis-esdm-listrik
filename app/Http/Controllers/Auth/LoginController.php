<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $name;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function show(){
        $data = [
            'title' => 'Login'
        ];

        return view('auth.login', $data);
    }

    public function login(Request $request){
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->attempt([$fieldType => $input['name'], 'password' => $input['password']])) {
            
            // Ambil user yg baru login
            $user = auth()->user();

            // Check if Email is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            
            // Check if user is verified (by Admin)
            if (!$user->is_verified) {
                return redirect()->route('pending-verification')
                    ->with('info', 'Akun Anda sedang menunggu verifikasi dari administrator.');
            }
            
            // Cek role pertamanya
            $role = $user->roles->first()?->name;

            // Logic Redirect:
            // 1. Admin & Superadmin => Dashboard
            if (in_array($role, ['admin', 'superadmin'])) {
                return redirect()->route('admin.dashboard')
                    ->with('login_success', 'Selamat datang kembali! Anda berhasil masuk ke Dashboard.');
            }
            
            // 2. Selain itu (Desa, Perusahaan, dll) => Halaman Peta (Landing)
            return redirect()->route('landing')
                ->with('login_success', 'Selamat datang kembali! Silakan lihat peta persebaran.');

        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'message' => 'Username atau Password tidak cocok!',
                ])
                ->with('login_error', 'Username atau password tidak cocok.');
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect()->route('login')->with('logout_success', 'Anda telah berhasil keluar dari aplikasi.');
    }
}

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

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'username' : 'name';
        if (auth()->attempt([$fieldType => $input['name'], 'password' => $input['password']])) {

            return redirect()->route('admin.dashboard')->with('login_success', 'Selamat datang kembali! Anda berhasil masuk.');
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

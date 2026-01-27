<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'identity_type' => ['required', 'string', 'in:desa,perusahaan'],
            'jabatan' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'address' => ['required', 'string'],
            'village_id' => ['required', 'exists:reg_villages,id'],
            'company_name' => ['required_if:identity_type,perusahaan', 'nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'jabatan' => $data['jabatan'],
            'company_name' => $data['company_name'] ?? null,
            'phone' => $data['phone'],
            'address' => $data['address'],
            'village_id' => $data['village_id'],
            'identity_type' => $data['identity_type'],
            'status' => 'aktif',
            'company_name' => $data['company_name'] ?? null,
        ]);

        // Create Perusahaan record if identity_type is perusahaan
        if ($data['identity_type'] === 'perusahaan') {
            $perusahaan = \App\Models\Perusahaan::create([
                'nama' => $data['company_name'],
                'village_id' => $data['village_id'],
                'alamat' => $data['address'],
                'kontak' => $data['phone'],
                'jenis_usaha' => 'Lainnya', // Default or null
                // 'kabupaten_kota' we skip for now as we have village_id relation
            ]);

            // Update user with perusahaan_id
            $user->perusahaan_id = $perusahaan->id;
            $user->save();
        }

        // Assign role (pastikan role sudah ada di database)
        if (isset($data['identity_type'])) {
            $user->assignRole($data['identity_type']);
        }

        return $user;
    }
}

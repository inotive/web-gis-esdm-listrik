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
            'document_verification' => ['nullable', 'file', 'mimes:pdf', 'max:2048'], // Max 2MB
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
        $documentPath = null;
        if (request()->hasFile('document_verification')) {
            $file = request()->file('document_verification');
            $documentPath = $file->store('verification_docs', 'public');
        } elseif (isset($data['document_verification']) && $data['document_verification'] instanceof \Illuminate\Http\UploadedFile) {
            // Fallback if request() is not available (though it usually is globally)
            $documentPath = $data['document_verification']->store('verification_docs', 'public');
        }

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
            'status' => 'tidak aktif',
            'document_verification_path' => $documentPath,
        ]);

        // Create Perusahaan record logic moved to Admin Approval (UserController@approve)
        // We only verify that identity_type is set correctly
        
        /* 
        if ($data['identity_type'] === 'perusahaan') {
             Logic moved to UserController::approve
        } 
        */

        // Assign role (pastikan role sudah ada di database)
        if (isset($data['identity_type'])) {
            $user->assignRole($data['identity_type']);
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     * Overrides RegistersUsers trait to prevent auto-login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(\Illuminate\Http\Request $request)
    {
        $this->validator($request->all())->validate();

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new \Illuminate\Http\JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }
}

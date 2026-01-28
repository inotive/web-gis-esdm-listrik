<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordOtp;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails; // Disable standard trait

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', ['title' => 'Lupa Password']);
    }

    public function sendResetOtp(Request $request) 
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar']);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        // Store to DB (update existing or create new)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp, // We store the plain OTP here for simplicity in this flow, usually hashed but user requested generic
                'created_at' => Carbon::now()
            ]
        );

        // Send Email
        try {
            Mail::to($user->email)->send(new ResetPasswordOtp($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }

        return redirect()->route('password.otp')->with('email', $request->email);
    }

    public function showOtpForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.otp', ['email' => session('email'), 'title' => 'Verifikasi OTP']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric' // We concatenate the 6 inputs in frontend or backend. Assuming simple input for now.
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || $record->token != $request->otp) {
            return back()->with('email', $request->email)->withErrors(['otp' => 'Kode OTP salah']);
        }

        // Check expiration (e.g. 15 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->with('email', $request->email)->withErrors(['otp' => 'Kode OTP sudah kadaluarsa']);
        }

        // OTP Valid -> Store flag in session to allow access to reset page
        session(['otp_verified_email' => $request->email]);

        return redirect()->route('password.reset-new');
    }

    public function showResetForm()
    {
        if (!session('otp_verified_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.reset', ['email' => session('otp_verified_email'), 'title' => 'Data Password Baru']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'email' => 'required|email'
        ]);

        // Verify session again
        if (session('otp_verified_email') !== $request->email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi tidak valid']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
             return back()->withErrors(['email' => 'User tidak ditemukan']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password)
        ])->save(); // No need to remember token update as we are manual

        // Delete OTP
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        session()->forget('otp_verified_email');

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login.');
    }
}

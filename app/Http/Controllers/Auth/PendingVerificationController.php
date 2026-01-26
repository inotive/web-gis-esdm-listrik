<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendingVerificationController extends Controller
{
    public function index()
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // If user is already verified, redirect to appropriate dashboard
        if (auth()->user()->is_verified) {
            if (auth()->user()->hasRole(['superadmin', 'admin'])) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('landing');
        }

        return view('auth.pending-verification', [
            'title' => 'Menunggu Verifikasi'
        ]);
    }
}

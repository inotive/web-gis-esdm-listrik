<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (!auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            if (!auth()->user()->is_verified) {
                return redirect()->route('pending-verification');
            }
        }
        
        return view('landing.index');
    }
}

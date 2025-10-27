<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InfrastrukturJaringanController extends Controller
{
    public function index()
    {
        // Tanpa DB: data dummy akan dirender di view
        return view('admin.infrastruktur.index');
    }
}

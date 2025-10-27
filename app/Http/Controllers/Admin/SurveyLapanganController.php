<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SurveyLapanganController extends Controller
{
    public function index()
    {
        // Tanpa DB: data dummy akan dirender di view
        return view('admin.survey.index');
    }
}

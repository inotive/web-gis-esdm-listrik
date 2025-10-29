<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Asset;
use App\Models\UnitKerja;
use App\Models\KategoriAsset;

class DashboardController extends Controller
{
    public function index(Request $request): View
{
    return view('admin.dashboard.index', [
        'title' => 'Dashboard Admin',
    ]);
}
}

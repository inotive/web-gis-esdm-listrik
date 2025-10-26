<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    //
    public function index()
    {
        $view = [
            'title' => "Manajemen Data Pelanggan",
        ];

        return view('admin.data_pelanggan.index', $view);
    }

}

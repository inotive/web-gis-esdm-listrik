<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataWilayahController extends Controller
{
    //
    public function index()
    {
        $view = [
            'title' => "Manajemen Data Wilayah",
        ];

        return view('admin.data_wilayah.index', $view);
    }
}

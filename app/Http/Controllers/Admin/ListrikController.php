<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListrikController extends Controller
{
    //
    public function index()
    {
        $view = [
            'title' => "Manajemen Listrik",
        ];

        return view('admin.listrik.index', $view);
    }
}

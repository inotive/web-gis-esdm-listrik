<?php

namespace App\Http\Controllers;

use App\Models\InfrastrukturJaringan;
use App\Models\Gardu;
use App\Models\PembangkitLokal;
use App\Models\LN_Transmisi;
use App\Models\LN_SUTM_Berau;
use App\Models\PT_Pembangkit_Eksisting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Hitung statistik infrastruktur
        // Cek apakah tabel utama kosong, jika ya gunakan data GIS
        $countJaringan = InfrastrukturJaringan::count();
        if ($countJaringan === 0) {
            $countJaringan = LN_Transmisi::count();
        }

        $countGardu = Gardu::count();
        if ($countGardu === 0) {
            $countGardu = LN_SUTM_Berau::count();
        }

        $countPembangkit = PembangkitLokal::count();
        if ($countPembangkit === 0) {
            $countPembangkit = PT_Pembangkit_Eksisting::count();
        }

        return view('home', [
            'countJaringan' => $countJaringan,
            'countGardu' => $countGardu,
            'countPembangkit' => $countPembangkit,
        ]);
    }
}

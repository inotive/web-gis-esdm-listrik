<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\DataBerlistrikController;
use App\Http\Controllers\Api\DatajalannasionalController;
use App\Http\Controllers\Api\DatajalanprovinsiController;
use App\Http\Controllers\Api\JaringanlistrikbalikpapanController;
use App\Http\Controllers\Api\JaringanlistrikbontangController;
use App\Http\Controllers\Api\SistemJaringanEnergiMahuluController;
use App\Http\Controllers\Api\SistemJaringanEnergiKukarController;
use App\Http\Controllers\Api\SistemJaringanEnergiKubarController;
use App\Http\Controllers\Api\SistemJaringanEnergiKubarUP2KBController;
use App\Http\Controllers\Api\SistemJaringanEnergiKutimController;
use App\Http\Controllers\Api\SistemJaringanEnergiPaserController;
use App\Http\Controllers\Api\SutrKutimController;
use App\Http\Controllers\Api\SutmPPUController;
use App\Http\Controllers\Api\SutmBerauController;
use App\Http\Controllers\Api\Ln2SutmPaserController;
use App\Http\Controllers\Api\Ln2SutmPPUController;
use App\Http\Controllers\Api\TransmisiController;
use App\Http\Controllers\Api\ArBatasKaltimFullController;
use App\Http\Controllers\Api\ArBatasKaltimKabupatenKotaController;
use App\Http\Controllers\Api\ArBatasKaltimKecamatanController;
use App\Http\Controllers\Api\LnBatasDesaController;
use App\Http\Controllers\Api\LnBatasKabupatenKotaController;
use App\Http\Controllers\Api\LnBatasKecamatanController;
use App\Http\Controllers\Api\LnBatasNegaraController;
use App\Http\Controllers\Api\LnBatasProvinsiController;
use App\Http\Controllers\Api\PtGarduBerauController;
use App\Http\Controllers\Api\PtGarduDistribusiKutimController;
use App\Http\Controllers\Api\PtGarduHubungKutimController;
use App\Http\Controllers\Api\PtGarduIndukKutimController;
use App\Http\Controllers\Api\PtPembangkitEksistingController;
use App\Http\Controllers\Api\PtRencanaPembangkitTenagaListrikBontangController;
use App\Http\Controllers\Api\PtSistemInfrastrukturEnergiBalikpapanController;
use App\Http\Controllers\Api\PtSistemInfrastrukturEnergiKukarController;
use App\Http\Controllers\Api\PtSistemInfrastrukturEnergiMahuluController;
use App\Http\Controllers\Api\PtSistemInfrastrukturEnergiSamarindaController;
use App\Http\Controllers\Api\PtTrafoBerauController;
use App\Http\Controllers\Api\PtTrafoGarduDistribusiPpuController;
use App\Http\Controllers\Api\PtTrafoGarduKubarController;
use App\Http\Controllers\Api\Pt1TrafoGarduPaserController;
use App\Http\Controllers\Api\Pt2TrafoGarduPaserController;
use App\Http\Controllers\Api\JalanBalikpapanController;
use App\Http\Controllers\Api\JalanKabupatenBerauController;

// Route::get('/aset', [AssetController::class, 'index']);
Route::get('/data-berlistrik', [DataBerlistrikController::class, 'index']);

Route::get('/data-jalan-nasional', [DatajalannasionalController::class, 'index']);
Route::get('/data-jalan-provinsi', [DatajalanprovinsiController::class, 'index']);
Route::get('/jalan-balikpapan', [JalanBalikpapanController::class, 'index']);
Route::get('/jalan-kabupaten-berau', [JalanKabupatenBerauController::class, 'index']);

Route::get('/jaringan-listrik-balikpapan', [JaringanlistrikbalikpapanController::class, 'index']);
Route::get('/jaringan-listrik-bontang', [JaringanlistrikbontangController::class, 'index']);

// 🔹 endpoint baru: Sistem Jaringan Energi Kukar
Route::get('/sistem-jaringan-energi-kukar', [SistemJaringanEnergiKukarController::class, 'index']);
Route::get('/sistem-jaringan-energi-mahulu', [SistemJaringanEnergiMahuluController::class, 'index']);
Route::get('/sistem-jaringan-energi-kubar', [SistemJaringanEnergiKubarController::class, 'index']);
Route::get('/sistem-jaringan-energi-kubar-up2kb', [SistemJaringanEnergiKubarUP2KBController::class, 'index']);
Route::get('/sistem-jaringan-energi-kutim', [SistemJaringanEnergiKutimController::class, 'index']);
Route::get('/sistem-jaringan-energi-paser', [SistemJaringanEnergiPaserController::class, 'index']);
Route::get('/sutr-kutim', [SutrKutimController::class, 'index']);
Route::get('/sutm-ppu', [SutmPPUController::class, 'index']);
Route::get('/sutm-berau', [SutmBerauController::class, 'index']);
Route::get('/ln2-sutm-paser', [Ln2SutmPaserController::class, 'index']);
Route::get('/ln2-sutm-ppu', [Ln2SutmPPUController::class, 'index']);
Route::get('/ln-transmisi', [TransmisiController::class, 'index']);
Route::get('/ln-batas-kabkota', [LnBatasKabupatenKotaController::class, 'index']);
Route::get('/ln-batas-kecamatan', [LnBatasKecamatanController::class, 'index']);
Route::get('/ln-batas-negara', [LnBatasNegaraController::class, 'index']);
Route::get('/ln-batas-provinsi', [LnBatasProvinsiController::class, 'index']);
Route::get('/ln-batas-desa', [LnBatasDesaController::class, 'index']);
Route::get('/pt-gardu-berau', [PtGarduBerauController::class, 'index']);
Route::get('/pt-gardu-distribusi-kutim', [PtGarduDistribusiKutimController::class, 'index']);
Route::get('/pt-gardu-hubung-kutim', [PtGarduHubungKutimController::class, 'index']);
Route::get('/pt-gardu-induk-kutim', [PtGarduIndukKutimController::class, 'index']);
Route::get('/pt-pembangkit-eksisting', [PtPembangkitEksistingController::class, 'index']);
Route::get('/pt-rencana-pembangkit-bontang', [PtRencanaPembangkitTenagaListrikBontangController::class, 'index']);
Route::get('/pt-sistem-energi-balikpapan', [PtSistemInfrastrukturEnergiBalikpapanController::class, 'index']);
Route::get('/pt-sistem-energi-kukar', [PtSistemInfrastrukturEnergiKukarController::class, 'index']);
Route::get('/pt-sistem-energi-mahulu', [PtSistemInfrastrukturEnergiMahuluController::class, 'index']);
Route::get('/pt-sistem-energi-samarinda', [PtSistemInfrastrukturEnergiSamarindaController::class, 'index']);
Route::get('/pt-trafo-berau', [PtTrafoBerauController::class, 'index']);
Route::get('/pt-trafo-gardu-distribusi-ppu', [PtTrafoGarduDistribusiPpuController::class, 'index']);
Route::get('/pt-trafo-gardu-kubar', [PtTrafoGarduKubarController::class, 'index']);
Route::get('/pt1-trafo-gardu-paser', [Pt1TrafoGarduPaserController::class, 'index']);
Route::get('/pt2-trafo-gardu-paser', [Pt2TrafoGarduPaserController::class, 'index']);
Route::get('/ar-batas-kaltim', [ArBatasKaltimFullController::class, 'index']);
Route::get('/ar-batas-kaltim-kabkota', [ArBatasKaltimKabupatenKotaController::class, 'index']);
Route::get('/ar-batas-kaltim-kecamatan', [ArBatasKaltimKecamatanController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

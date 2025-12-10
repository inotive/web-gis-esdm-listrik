<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
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

Route::get('/aset', [AssetController::class, 'index']);
Route::get('/data-berlistrik', [DataBerlistrikController::class, 'index']);

Route::get('/data-jalan-nasional', [DatajalannasionalController::class, 'index']);
Route::get('/data-jalan-provinsi', [DatajalanprovinsiController::class, 'index']);

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
Route::get('/ar-batas-kaltim', [ArBatasKaltimFullController::class, 'index']);
Route::get('/ar-batas-kaltim-kabkota', [ArBatasKaltimKabupatenKotaController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

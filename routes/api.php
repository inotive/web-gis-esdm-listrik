<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\DataBerlistrikController;
use App\Http\Controllers\Api\DatajalannasionalController;
use App\Http\Controllers\Api\DatajalanprovinsiController;
use App\Http\Controllers\Api\JaringanlistrikbalikpapanController;
use App\Http\Controllers\Api\JaringanlistrikbontangController;

Route::get('/aset', [AssetController::class, 'index']);
Route::get('/data-berlistrik', [DataBerlistrikController::class, 'index']);

Route::get('/data-jalan-nasional', [DatajalannasionalController::class, 'index']);
Route::get('/data-jalan-provinsi', [DatajalanprovinsiController::class, 'index']);

Route::get('/jaringan-listrik-balikpapan', [JaringanlistrikbalikpapanController::class, 'index']);

// 🔹 endpoint baru: rencana jaringan listrik Bontang
Route::get('/jaringan-listrik-bontang', [JaringanlistrikbontangController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

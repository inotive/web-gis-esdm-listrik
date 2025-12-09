<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\DataBerlistrikController;
use App\Http\Controllers\Api\DatapttrafoController; // ← tambahkan ini

Route::get('/aset', [AssetController::class, 'index']);
Route::get('/data-berlistrik', [DataBerlistrikController::class, 'index']);

// API baru untuk PT_Trafo_Berau
Route::get('/pt-trafo', [DatapttrafoController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

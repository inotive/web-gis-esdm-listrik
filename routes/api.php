<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\DataBerlistrikController; // ← TAMBAHAN

Route::get('/aset', [AssetController::class, 'index']);

// API baru untuk desa berlistrik
Route::get('/data-berlistrik', [DataBerlistrikController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

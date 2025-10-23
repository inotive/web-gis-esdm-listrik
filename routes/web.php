<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\KategoriAssetController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StatusHukumAssetController;
use App\Http\Controllers\Admin\UnitKerjaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AssetDokumenController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login-post');

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::group(['middleware' => ['auth'], 'as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => [], 'as' => 'profile.', 'prefix' => 'profile'], function () {
        Route::get('profile/{profile}', [ProfileController::class, 'profile'])->name('index');
        Route::put('profile/{profile}/update-profile', [ProfileController::class, 'updateProfile'])->name('profile-update');
    });

    Route::resource('dokumen-asset', AssetDokumenController::class)
        ->parameters(['dokumen-asset' => 'dokumen'])
        ->except(['show']);

    // Tambahan aksi
    Route::get('dokumen-asset/print', [AssetDokumenController::class, 'print'])->name('dokumen-asset.print');
    Route::get('dokumen-asset/export', [AssetDokumenController::class, 'export'])->name('dokumen-asset.export');
    Route::get('dokumen-asset/{dokumen}/download', [AssetDokumenController::class, 'download'])->name('dokumen-asset.download');
    Route::get('dokumen-asset/{dokumen}/view', [AssetDokumenController::class, 'view'])->name('dokumen-asset.view');
    Route::delete('dokumen-asset/{dokumen}/file', [AssetDokumenController::class, 'deleteFile'])->name('dokumen-asset.file.delete');

     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::group(['middleware' => [], 'as' => 'hak-akses.', 'prefix' => 'hak-akses'], function () {
        Route::resource('role', RoleController::class)->except('show', 'create', 'edit');
        Route::resource('permission', PermissionController::class)->except('show', 'create', 'edit');
        Route::resource('user', UserController::class)->except('show');
    });

    Route::resource('kategori-asset', KategoriAssetController::class)->except('show');
    Route::resource('status-hukum-asset', StatusHukumAssetController::class)->except('show');
    Route::resource('unit-kerja', UnitKerjaController::class)->except('show');

    Route::group(['prefix' => 'asset', 'as' => 'asset.'], function () {
        // CRUD Routes
        Route::get('/', [AssetController::class, 'index'])->name('index');
        Route::get('/create', [AssetController::class, 'create'])->name('create');
        Route::get('/{asset}/edit', [AssetController::class, 'edit'])->name('edit');
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::put('/{asset}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{asset}', [AssetController::class, 'destroy'])->name('destroy');

        // ✅ NEW ROUTES
        Route::get('/peta-persebaran', [AssetController::class, 'petaPersebaran'])->name('peta-persebaran');
        Route::get('/rekapitulasi', [AssetController::class, 'rekapitulasi'])->name('rekapitulasi');
        Route::get('/dokumen', [AssetController::class, 'dokumen'])->name('dokumen');
        Route::get('/rekapitulasi/export', [AssetController::class, 'exportRekapitulasi'])->name('rekapitulasi.export');
        // Ringkasan rekapitulasi (print)
        Route::get('/rekapitulasi/print', [AssetController::class, 'printRekapitulasi'])->name('rekapitulasi.print');


        // Legacy routes (keep for compatibility)
        Route::get('/kategori', [AssetController::class, 'kategori'])->name('kategori');
        Route::get('/status-hukum', [AssetController::class, 'statusHukum'])->name('status-hukum');
        Route::get('/unit-kerja', [AssetController::class, 'unitKerja'])->name('unit-kerja');

          // ✅ Sertifikat Routes
    Route::get('/{asset}/sertifikat/download', [AssetController::class, 'downloadSertifikat'])->name('sertifikat.download');
    Route::get('/{asset}/sertifikat/view', [AssetController::class, 'viewSertifikat'])->name('sertifikat.view');
    Route::delete('/{asset}/sertifikat', [AssetController::class, 'deleteSertifikat'])->name('sertifikat.delete');
 Route::get('/statistics', [AssetController::class, 'getStatistics'])->name('statistics');
    Route::post('/validate-coordinates', [AssetController::class, 'validateCoordinates'])->name('validate-coordinates');
    Route::post('/check-kode', [AssetController::class, 'checkKodeAsset'])->name('check-kode');
    });

    Route::resource('jabatan', JabatanController::class)
        ->except(['create', 'edit', 'show'])
        ->middleware('admin');
});



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
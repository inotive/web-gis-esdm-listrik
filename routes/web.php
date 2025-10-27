<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UnitKerjaController;
use App\Http\Controllers\Admin\DataWilayahController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\InfrastrukturJaringanController;
use App\Http\Controllers\Admin\SurveyLapanganController;

// Tambahan controllers baru
use App\Http\Controllers\Admin\DataGarduController;
use App\Http\Controllers\Admin\PembangkitLokalController;
use App\Http\Controllers\Admin\JalanAksesController;
use App\Http\Controllers\Admin\SkoringBobotController;
use App\Http\Controllers\Admin\PemukimanTanpaListrikController;
use App\Http\Controllers\Admin\GisController;

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

   
        
        Route::group(['as' => 'hak-akses.', 'prefix' => 'hak-akses'], function () {
        // Role Management
        Route::middleware('can:role.view')->group(function () {
            Route::get('role', [RoleController::class, 'index'])->name('role.index');
            Route::get('role/{role}/permissions', [RoleController::class, 'permissions'])->name('role.permissions');
        });
        
        Route::post('role', [RoleController::class, 'store'])->middleware('can:role.create')->name('role.store');
        Route::put('role/{role}', [RoleController::class, 'update'])->middleware('can:role.edit')->name('role.update');
        Route::delete('role/{role}', [RoleController::class, 'destroy'])->middleware('can:role.delete')->name('role.destroy');
        Route::put('role/{role}/permissions', [RoleController::class, 'updatePermissions'])->middleware('can:role.permission')->name('role.permissions.update');
        
        // Permission Management
        Route::resource('permission', PermissionController::class)->except('show', 'create', 'edit');
        
        // User Management
        Route::middleware('can:user.view')->group(function () {
            Route::resource('user', UserController::class)->except('show');
        });
    });
    // Data Wilayah
    Route::group(['middleware' => [], 'as' => 'data-wilayah.', 'prefix' => 'data-wilayah'], function () {
        Route::get('/', [DataWilayahController::class, 'index'])->name('index');
    });

    // Data Pelanggan
    Route::group(['middleware' => [], 'as' => 'pelanggan.', 'prefix' => 'pelanggan'], function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
    });

    // ===== Tambahan menu baru =====
    Route::group(['as' => 'gardu.', 'prefix' => 'gardu'], function () {
        Route::get('/', [DataGarduController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'pembangkit.', 'prefix' => 'pembangkit-lokal'], function () {
        Route::get('/', [PembangkitLokalController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'jalan.', 'prefix' => 'jalan-akses'], function () {
        Route::get('/', [JalanAksesController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'skoring.', 'prefix' => 'skoring-bobot'], function () {
        Route::get('/', [SkoringBobotController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'pemukiman.', 'prefix' => 'pemukiman-tanpa-listrik'], function () {
        Route::get('/', [PemukimanTanpaListrikController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'gis.', 'prefix' => 'gis'], function () {
        Route::get('/', [GisController::class, 'index'])->name('index');
    });

     Route::group(['as' => 'infrastruktur.', 'prefix' => 'infrastruktur-jaringan'], function () {
        Route::get('/', [InfrastrukturJaringanController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'survey.', 'prefix' => 'hasil-survei-lapangan'], function () {
        Route::get('/', [SurveyLapanganController::class, 'index'])->name('index');
    });
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

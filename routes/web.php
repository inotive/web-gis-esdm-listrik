<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UnitKerjaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\DataWilayahController;
use App\Http\Controllers\Admin\PelangganController;


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
    
    Route::group(['middleware' => [], 'as' => 'hak-akses.', 'prefix' => 'hak-akses'], function () {
        Route::resource('role', RoleController::class)->except('show', 'create', 'edit');
        Route::resource('permission', PermissionController::class)->except('show', 'create', 'edit');
        Route::resource('user', UserController::class)->except('show');
    });

     // Data Wilayah
    Route::group(['middleware' => [], 'as' => 'data-wilayah.', 'prefix' => 'data-wilayah'], function () {
        Route::get('/', [DataWilayahController::class, 'index'])->name('index');
        
    });

     Route::group(['middleware' => [], 'as' => 'pelanggan.', 'prefix' => 'pelanggan'], function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
       
    });

   
});



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
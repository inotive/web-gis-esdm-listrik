<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicRegionController;
use App\Http\Controllers\HomeController;
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
use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Admin\PerusahaanController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\Admin\PermohonanUserController;
use App\Http\Controllers\Admin\DokumenController;
use App\Http\Controllers\Admin\RekapDataController;
use App\Http\Controllers\Admin\DataInfrastrukturController;
use App\Http\Controllers\Admin\KategoriPermohonanController;
use App\Http\Controllers\Admin\PerizinanController;
use App\Http\Controllers\Admin\PengajuanPermohonanController;

// Models untuk statistik home
use App\Models\InfrastrukturJaringan;
use App\Models\Gardu;
use App\Models\PembangkitLokal;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.perform');

// Public Region API for Registration
Route::get('/ajax/regions/regencies', [PublicRegionController::class, 'regencies'])->name('ajax.regions.regencies');
Route::get('/ajax/regions/districts', [PublicRegionController::class, 'districts'])->name('ajax.regions.districts');
Route::get('/ajax/regions/villages', [PublicRegionController::class, 'villages'])->name('ajax.regions.villages');
Route::post('login', [LoginController::class, 'login'])->name('login-post');

Route::get('/', [HomeController::class, 'index']);
Route::get('/', function () {
    $infrastrukturCount = InfrastrukturJaringan::count();
    $garduCount = Gardu::count();
    $pembangkitCount = PembangkitLokal::count();

    return view('home', compact('infrastrukturCount', 'garduCount', 'pembangkitCount'));
});

Route::get('/home', function () {
    return view('home');
});


// Landing (peta) - bisa diakses tanpa login
Route::get('/landing', [LandingPageController::class, 'index'])
    ->name('landing');

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
        Route::post('/', [DataWilayahController::class, 'store'])->name('store');
        Route::get('/{wilayah}/edit', [DataWilayahController::class, 'edit'])->name('edit');
        Route::put('/{wilayah}', [DataWilayahController::class, 'update'])->name('update');
        Route::delete('/{wilayah}', [DataWilayahController::class, 'destroy'])->name('destroy');

        // Endpoints opsi untuk dropdown berjenjang
        Route::get('/options/regencies', [DataWilayahController::class, 'optionsRegencies'])->name('options.regencies');
        Route::get('/options/districts', [DataWilayahController::class, 'optionsDistricts'])->name('options.districts');
        Route::get('/options/villages', [DataWilayahController::class, 'optionsVillages'])->name('options.villages');
    });

    // Data Pelanggan
    Route::group(['middleware' => [], 'as' => 'pelanggan.', 'prefix' => 'pelanggan'], function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::post('/', [PelangganController::class, 'store'])->name('store');
        Route::put('/{pelanggan}', [PelangganController::class, 'update'])->name('update');
        Route::delete('/{pelanggan}', [PelangganController::class, 'destroy'])->name('destroy');
    });

    // Data Desa
    Route::group(['middleware' => [], 'as' => 'desa.', 'prefix' => 'desa'], function () {
        Route::get('/', [DesaController::class, 'index'])->name('index');
        Route::post('/', [DesaController::class, 'store'])->name('store');
        Route::get('/{desa}/edit', [DesaController::class, 'edit'])->name('edit');
        Route::put('/{desa}', [DesaController::class, 'update'])->name('update');
        Route::delete('/{desa}', [DesaController::class, 'destroy'])->name('destroy');

        // Endpoints opsi untuk dropdown berjenjang
        Route::get('/options/regencies', [DesaController::class, 'optionsRegencies'])->name('options.regencies');
        Route::get('/options/districts', [DesaController::class, 'optionsDistricts'])->name('options.districts');
    });

    // Data Perusahaan
    Route::group(['middleware' => [], 'as' => 'perusahaan.', 'prefix' => 'perusahaan'], function () {
        Route::get('/', [PerusahaanController::class, 'index'])->name('index');
        Route::post('/', [PerusahaanController::class, 'store'])->name('store');
        Route::get('/{perusahaan}', [PerusahaanController::class, 'show'])->name('show');
        Route::get('/{perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('edit');
        Route::put('/{perusahaan}', [PerusahaanController::class, 'update'])->name('update');
        Route::delete('/{perusahaan}', [PerusahaanController::class, 'destroy'])->name('destroy');

        // Endpoints opsi untuk dropdown berjenjang
        Route::get('/options/regencies', [PerusahaanController::class, 'optionsRegencies'])->name('options.regencies');
        Route::get('/options/districts', [PerusahaanController::class, 'optionsDistricts'])->name('options.districts');
        Route::get('/options/villages', [PerusahaanController::class, 'optionsVillages'])->name('options.villages');
    });

    // Data Infrastruktur (Gabungan dengan Tabs)
    Route::get('/data-infrastruktur', [DataInfrastrukturController::class, 'index'])->name('data-infrastruktur.index');

    // routes/web.php

    Route::group(['as' => 'gardu.', 'prefix' => 'gardu'], function () {
        Route::get('/', [DataGarduController::class, 'index'])->name('index');
        Route::get('/create', [DataGarduController::class, 'create'])->name('create');
        Route::post('/', [DataGarduController::class, 'store'])->name('store');
        Route::get('/{gardu}/edit', [DataGarduController::class, 'edit'])->name('edit');
        Route::put('/{gardu}', [DataGarduController::class, 'update'])->name('update');
        Route::delete('/{gardu}', [DataGarduController::class, 'destroy'])->name('destroy');
    });


    // routes/web.php

    Route::group(['as' => 'pembangkit.', 'prefix' => 'pembangkit-lokal'], function () {
        Route::get('/', [PembangkitLokalController::class, 'index'])->name('index');
        Route::get('/create', [PembangkitLokalController::class, 'create'])->name('create');
        Route::post('/', [PembangkitLokalController::class, 'store'])->name('store');
        Route::get('/{pembangkit}/edit', [PembangkitLokalController::class, 'edit'])->name('edit');
        Route::put('/{pembangkit}', [PembangkitLokalController::class, 'update'])->name('update');
        Route::delete('/{pembangkit}', [PembangkitLokalController::class, 'destroy'])->name('destroy');
    });


    Route::group(['as' => 'jalan.', 'prefix' => 'jalan-akses'], function () {
        Route::get('/', [JalanAksesController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'skoring.', 'prefix' => 'skoring-bobot'], function () {
        Route::get('/', [SkoringBobotController::class, 'index'])->name('index');
        Route::post('/', [SkoringBobotController::class, 'store'])->name('store');
        Route::put('/{variabel}', [SkoringBobotController::class, 'update'])->name('update');
        Route::delete('/{variabel}', [SkoringBobotController::class, 'destroy'])->name('destroy');
    });

    Route::group(['as' => 'pemukiman.', 'prefix' => 'pemukiman-tanpa-listrik'], function () {
        Route::get('/', [PemukimanTanpaListrikController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'gis.', 'prefix' => 'gis'], function () {
        Route::get('/', [GisController::class, 'index'])->name('index');
    });

    Route::group(['as' => 'infrastruktur.', 'prefix' => 'infrastruktur-jaringan'], function () {
        Route::get('/', [InfrastrukturJaringanController::class, 'index'])->name('index');
        Route::post('/', [InfrastrukturJaringanController::class, 'store'])->name('store');
        Route::put('/{infrastruktur}', [InfrastrukturJaringanController::class, 'update'])->name('update');
        Route::delete('/{infrastruktur}', [InfrastrukturJaringanController::class, 'destroy'])->name('destroy');
    });


    Route::group(['as' => 'survey.', 'prefix' => 'hasil-survei-lapangan'], function () {
        Route::get('/', [SurveyLapanganController::class, 'index'])->name('index');
    });

    // Permohonan
    Route::group(['as' => 'permohonan.', 'prefix' => 'permohonan'], function () {
        Route::get('/', [PermohonanController::class, 'index'])->name('index');
        Route::get('/create', [PermohonanController::class, 'create'])->name('create');
        Route::post('/', [PermohonanController::class, 'store'])->name('store');
        Route::get('/{permohonan}/edit', [PermohonanController::class, 'edit'])->name('edit');
        Route::put('/{permohonan}', [PermohonanController::class, 'update'])->name('update');
        Route::delete('/{permohonan}', [PermohonanController::class, 'destroy'])->name('destroy');

        // Endpoints untuk dropdown berjenjang
        Route::get('/options/districts', [PermohonanController::class, 'optionsDistricts'])->name('options.districts');
        Route::get('/options/villages', [PermohonanController::class, 'optionsVillages'])->name('options.villages');
    });

    // Perizinan
    Route::group(['as' => 'perizinan.', 'prefix' => 'perizinan'], function () {
        Route::get('/', [PerizinanController::class, 'index'])->name('index');
        Route::get('/create', [PerizinanController::class, 'create'])->name('create');
        Route::post('/', [PerizinanController::class, 'store'])->name('store');
        Route::get('/{perizinan}', [PerizinanController::class, 'show'])->name('show');
        Route::get('/{perizinan}/edit', [PerizinanController::class, 'edit'])->name('edit');
        Route::put('/{perizinan}', [PerizinanController::class, 'update'])->name('update');
        Route::delete('/{perizinan}', [PerizinanController::class, 'destroy'])->name('destroy');
        Route::post('/{perizinan}/document', [PerizinanController::class, 'addDocument'])->name('document.add');
        Route::delete('/{perizinan}/document/{document}', [PerizinanController::class, 'deleteDocument'])->name('document.delete');
    });

    Route::group(['as' => 'kategori-permohonan.', 'prefix' => 'kategori-permohonan'], function () {
        Route::get('/', [KategoriPermohonanController::class, 'index'])->name('index');
        Route::get('/create', [KategoriPermohonanController::class, 'create'])->name('create');
        Route::post('/', [KategoriPermohonanController::class, 'store'])->name('store');
        Route::get('/{permohonan}', [KategoriPermohonanController::class, 'show'])->name('show');
        Route::get('/{permohonan}/edit', [KategoriPermohonanController::class, 'edit'])->name('edit');
        Route::put('/{permohonan}', [KategoriPermohonanController::class, 'update'])->name('update');
        Route::delete('/{permohonan}', [KategoriPermohonanController::class, 'destroy'])->name('destroy');
    });

    // Permohonan User (Old - for admin to manage)
    Route::group(['as' => 'permohonan-user.', 'prefix' => 'permohonan-user'], function () {
        Route::get('/{permohonanId}', [PermohonanUserController::class, 'index'])->name('index');
        Route::get('/{permohonanId}/create', [PermohonanUserController::class, 'create'])->name('create');
        Route::post('/{permohonanId}', [PermohonanUserController::class, 'store'])->name('store');
        Route::get('/{permohonanId}/{permohonanUser}', [PermohonanUserController::class, 'show'])->name('show');
        Route::get('/{permohonanId}/{permohonanUser}/edit', [PermohonanUserController::class, 'edit'])->name('edit');
        Route::put('/{permohonanId}/{permohonanUser}', [PermohonanUserController::class, 'update'])->name('update');
        Route::delete('/{permohonanId}/{permohonanUser}', [PermohonanUserController::class, 'destroy'])->name('destroy');
        Route::post('/{permohonanId}/{permohonanUser}/approve', [PermohonanUserController::class, 'approve'])->name('approve');
        Route::post('/{permohonanId}/{permohonanUser}/reject', [PermohonanUserController::class, 'reject'])->name('reject');
        Route::post('/{permohonanId}/{permohonanUser}/progress', [PermohonanUserController::class, 'progress'])->name('progress');
        Route::post('/{permohonanId}/{permohonanUser}/cancel', [PermohonanUserController::class, 'cancel'])->name('cancel');
        Route::post('/{permohonanId}/{permohonanUser}/document', [PermohonanUserController::class, 'addDocument'])->name('document.add');
        Route::delete('/{permohonanId}/{permohonanUser}/document/{document}', [PermohonanUserController::class, 'deleteDocument'])->name('document.delete');
    });

    // Pengajuan Permohonan (New - simplified for desa/perusahaan users)
    Route::group(['as' => 'pengajuan-permohonan.', 'prefix' => 'pengajuan-permohonan'], function () {
        Route::get('/', [PengajuanPermohonanController::class, 'index'])->name('index');
        Route::get('/select-type', [PengajuanPermohonanController::class, 'selectType'])->name('select-type');
        Route::get('/create', [PengajuanPermohonanController::class, 'create'])->name('create');
        Route::post('/', [PengajuanPermohonanController::class, 'store'])->name('store');
        Route::get('/{pengajuanPermohonan}', [PengajuanPermohonanController::class, 'show'])->name('show');
        Route::get('/{pengajuanPermohonan}/edit', [PengajuanPermohonanController::class, 'edit'])->name('edit');
        Route::put('/{pengajuanPermohonan}', [PengajuanPermohonanController::class, 'update'])->name('update');
        Route::delete('/{pengajuanPermohonan}', [PengajuanPermohonanController::class, 'destroy'])->name('destroy');
    });

    // Dokumen
    Route::group(['as' => 'dokumen.', 'prefix' => 'dokumen'], function () {
        Route::get('/', [DokumenController::class, 'index'])->name('index');
        Route::post('/folder', [DokumenController::class, 'storeFolder'])->name('folder.store');
        Route::post('/file', [DokumenController::class, 'storeFiles'])->name('file.store');
        Route::put('/{dokumen}', [DokumenController::class, 'update'])->name('update');
        Route::delete('/{dokumen}', [DokumenController::class, 'destroy'])->name('destroy');
        Route::get('/{dokumen}/download', [DokumenController::class, 'download'])->name('download');
    });

    // Rekap Data
    Route::group(['as' => 'rekap-data.', 'prefix' => 'rekap-data'], function () {
        Route::get('/', [RekapDataController::class, 'index'])->name('index');
        Route::get('/detail/{kabupaten}', [RekapDataController::class, 'detail'])->name('detail');
    });
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

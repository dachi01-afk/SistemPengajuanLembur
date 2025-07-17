<?php

use App\Http\Controllers\apps\UserController;
use App\Http\Controllers\apps\PositionController;
use App\Http\Controllers\apps\DepartmentController;
use App\Http\Controllers\apps\PengajuanLemburController;
use App\Http\Controllers\testingController;
use Illuminate\Support\Facades\Route;

Route::get('testing', function () {
    return view('testing');
});

Route::get('/', function () {
    return view('Admin/dashboard');
});

// Route::prefix('apps')->group(function () {
//     Route::get('/', function () {
//         return view('Admin/dashboard');
//     });

//     Route::prefix('admin')->group(function () {

//         Route::get('/', function () {
//             return view('Admin/dashboard');
//         });

//         Route::prefix('pegawai')->name('pegawai.')->group(function () {

//             Route::get('/',                 [UserController::class, 'index'])->name('index');
//             Route::get('/data',             [UserController::class, 'getShowData'])->name('data');

//             Route::post('/create',          [UserController::class, 'insertData'])->name('create');
//             Route::get('/edit',             [UserController::class, 'Edit'])->name('edit');
//             Route::put('/update/{id}',      [UserController::class, 'updateData'])->name('update');
//             Route::delete('/delete/{id}',   [UserController::class, 'deleteData'])->name('delete');
//         });

//         Route::prefix('position')->name('position.')->group(function () {

//             Route::get('/',                 [PositionController::class, 'index'])->name('index');
//             Route::get('/getyId/{id}',      [PositionController::class, 'getById'])->name('getbyid');

//             Route::post('/create',          [PositionController::class, 'insertData'])->name('create');
//             Route::put('/update/{id}',      [PositionController::class, 'updateData'])->name('update');
//             Route::delete('/delete/{id}',   [PositionController::class, 'deleteData'])->name('delete');
//         });

//         Route::prefix('department')->name('department.')->group(function () {

//             Route::get('/',                 [DepartmentController::class, 'index'])->name('index');
//             Route::get('/getyId/{id}',      [DepartmentController::class, 'getById'])->name('getbyid');

//             Route::post('/create',          [DepartmentController::class, 'insertData'])->name('create');
//             Route::put('/update/{id}',      [DepartmentController::class, 'updateData'])->name('update');
//             Route::delete('/delete/{id}',   [DepartmentController::class, 'deleteData'])->name('delete');
//         });
//     });
// });


Route::prefix('apps')->group(function () {
    // Halaman dashboard utama
    Route::get('/', fn() => view('Admin/dashboard'))->name('dashboard');

    // ================================
    // ========== ADMIN ==============
    // ================================
    Route::prefix('admin')->group(function () {

        Route::get('/', fn() => view('Admin/dashboard'))->name('admin.dashboard');

        // Data Pegawai
        Route::prefix('pegawai')->name('pegawai.')->group(function () {
            Route::get('/',                 [UserController::class, 'index'])->name('index');
            Route::get('/data',             [UserController::class, 'getShowData'])->name('data');
            Route::post('/create',          [UserController::class, 'insertData'])->name('create');
            Route::get('/edit',             [UserController::class, 'Edit'])->name('edit');
            Route::put('/update/{id}',      [UserController::class, 'updateData'])->name('update');
            Route::delete('/delete/{id}',   [UserController::class, 'deleteData'])->name('delete');
        });

        // Data Jabatan
        Route::prefix('position')->name('position.')->group(function () {
            Route::get('/',                 [PositionController::class, 'index'])->name('index');
            Route::get('/getyId/{id}',      [PositionController::class, 'getById'])->name('getbyid');
            Route::post('/create',          [PositionController::class, 'insertData'])->name('create');
            Route::put('/update/{id}',      [PositionController::class, 'updateData'])->name('update');
            Route::delete('/delete/{id}',   [PositionController::class, 'deleteData'])->name('delete');
        });

        // Data Departemen
        Route::prefix('department')->name('department.')->group(function () {
            Route::get('/',                 [DepartmentController::class, 'index'])->name('index');
            Route::get('/getyId/{id}',      [DepartmentController::class, 'getById'])->name('getbyid');
            Route::post('/create',          [DepartmentController::class, 'insertData'])->name('create');
            Route::put('/update/{id}',      [DepartmentController::class, 'updateData'])->name('update');
            Route::delete('/delete/{id}',   [DepartmentController::class, 'deleteData'])->name('delete');
        });

        // Pengajuan Lembur
        Route::get('/pengajuan',            [PengajuanLemburController::class, 'index'])->name('admin.pengajuan.index');

        // Approval Lembur
        // Route::get('/approval',             [ApprovalController::class, 'index'])->name('admin.approval.index');

        // Riwayat Lembur
        // Route::get('/riwayat',              [RiwayatController::class, 'index'])->name('admin.riwayat.index');

        // Laporan
        // Route::get('/laporan',              [ReportController::class, 'index'])->name('admin.laporan.index');

        // User Management
        // Route::get('/user-management',      [UserManagementController::class, 'index'])->name('admin.user.index');

        // Settings
        // Route::get('/settings',             [SettingController::class, 'index'])->name('admin.settings.index');

        // Profile
        // Route::get('/profile',              [ProfileController::class, 'index'])->name('admin.profile.index');
    });

    // ================================
    // ========== ATASAN =============
    // ================================
    Route::prefix('atasan')->group(function () {
        Route::get('/', fn() => view('Atasan/dashboard'))->name('atasan.dashboard');

        // Route::get('/pengajuan',             [PengajuanLemburController::class, 'index'])->name('atasan.pengajuan.index');
        // Route::get('/approval',              [ApprovalController::class, 'index'])->name('atasan.approval.index');
        // Route::get('/riwayat',               [RiwayatController::class, 'index'])->name('atasan.riwayat.index');
        // Route::get('/profile',               [ProfileController::class, 'index'])->name('atasan.profile.index');
    });

    // ================================
    // ========== PEGAWAI ============
    // ================================
    Route::prefix('pegawai')->group(function () {
        Route::get('/', fn() => view('Pegawai/dashboard'))->name('pegawai.dashboard');

        // Route::get('/pengajuan',             [PengajuanLemburController::class, 'index'])->name('pegawai.pengajuan.index');
        // Route::get('/riwayat',               [RiwayatController::class, 'index'])->name('pegawai.riwayat.index');
        // Route::get('/profile',               [ProfileController::class, 'index'])->name('pegawai.profile.index');
    });
});

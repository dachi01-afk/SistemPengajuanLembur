<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\apps\Admin\UserController;
// admin
use App\Http\Controllers\apps\Admin\PositionController;
use App\Http\Controllers\apps\Admin\DepartmentController;
use App\Http\Controllers\apps\Admin\ReportAdminController;
use App\Http\Controllers\apps\Admin\HistoryAdminController;
use App\Http\Controllers\apps\Admin\ApprovalAdminController;
use App\Http\Controllers\apps\Admin\DashboardAdminController;
use App\Http\Controllers\apps\Atasan\HistoryAtasanController;

// atasan

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\apps\Atasan\ApprovalAtasanController;
use App\Http\Controllers\apps\Atasan\DashboardAtasanController;

// pegawai
use App\Http\Controllers\apps\Pegawai\HistoryPegawaiController;
use App\Http\Controllers\apps\Pegawai\DashboardPegawaiController;
use App\Http\Controllers\apps\Admin\OvertimeRequestAdminController;
use App\Http\Controllers\apps\Atasan\OvertimeRequestAtasanController;
use App\Http\Controllers\apps\Pegawai\PenugasanLemburController;


// Route::get('/', [AuthenticatedSessionController::class, 'create'])
//     return view('login');
// });

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// ========================================
//            AUTHENTICATED ROUTES
// ========================================
Route::middleware('auth')->group(
    function () {

        // ========= PROFILE ==========
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/redirect-by-role', function () {
            $user = Auth::user();

            // debbud sementara
            // logger('Redirect Role', ['role_id' => $user->role_id, 'role' => $user->role]);
            $roleName = $user->role->role ?? null;

            switch ($roleName) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'atasan':
                    return redirect()->route('atasan.dashboard');
                case 'pegawai':
                    return redirect()->route('pegawai.dashboard');
                default:
                    abort(403, 'Unauthorized');
            }
        })->middleware(['auth', 'verified']);


        // ========= APLIKASI ==========
        Route::prefix('apps')->group(function () {

            Route::get('/',                     [DashboardAdminController::class, 'index'])->name('admin.dashboard');
            Route::get('/',                     [DashboardAtasanController::class, 'index'])->name('atasan.dashboard');
            Route::get('/',                     [DashboardPegawaiController::class, 'index'])->name('pegawai.dashboard');

            // ================================
            // ========== ADMIN ==============
            // ================================
            Route::prefix('admin')->middleware('role:admin')->group(function () {

                Route::get('/',                     [DashboardAdminController::class, 'index'])->name('admin.dashboard');

                // Data Pegawai
                Route::prefix('pegawai')->name('pegawai.')->group(function () {
                    Route::get('/',                           [UserController::class, 'index'])->name('index');
                    Route::get('/data',                       [UserController::class, 'getShowData'])->name('data');
                    Route::post('/create',                    [UserController::class, 'insertData'])->name('create');
                    Route::get('/edit',                       [UserController::class, 'Edit'])->name('edit');
                    Route::put('/update/{id}',                [UserController::class, 'updateData'])->name('update');
                    Route::delete('/delete/{id}',             [UserController::class, 'deleteData'])->name('delete');
                });

                // Data Jabatan
                Route::prefix('position')->name('position.')->group(function () {
                    Route::get('/',                           [PositionController::class, 'index'])->name('index');
                    Route::get('/getyId/{id}',                [PositionController::class, 'getById'])->name('getbyid');
                    Route::post('/create',                    [PositionController::class, 'insertData'])->name('create');
                    Route::put('/update/{id}',                [PositionController::class, 'updateData'])->name('update');
                    Route::delete('/delete/{id}',             [PositionController::class, 'deleteData'])->name('delete');
                });

                // Data Departemen
                Route::prefix('department')->name('department.')->group(function () {
                    Route::get('/',                           [DepartmentController::class, 'index'])->name('index');
                    Route::get('/getyId/{id}',                [DepartmentController::class, 'getById'])->name('getbyid');
                    Route::post('/create',                    [DepartmentController::class, 'insertData'])->name('create');
                    Route::put('/update/{id}',                [DepartmentController::class, 'updateData'])->name('update');
                    Route::delete('/delete/{id}',             [DepartmentController::class, 'deleteData'])->name('delete');
                });

                // Pengajuan Lembur
                // Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
                //     Route::get('/',                           [OvertimeRequestAdminController::class, 'index'])->name('index.admin');
                //     Route::get('/get-user-department/{id}',   [OvertimeRequestAdminController::class, 'getUserDepartment'])->name('get-user-department');
                //     Route::post('/create',                    [OvertimeRequestAdminController::class, 'insertData'])->name('createbyadmin');
                // });

                // Approval Lembur
                // Route::prefix('approval')->name('approval.')->group(function () {
                //     Route::get('/',                           [ApprovalAdminController::class, 'index'])->name('index');
                //     Route::patch('/approve/{id}',             [ApprovalAdminController::class, 'Approve'])->name('approve');
                //     Route::patch('/reject/{id}',              [ApprovalAdminController::class, 'Reject'])->name('reject');
                //     Route::get('/detail/{id}',                [ApprovalAdminController::class, 'showDetail'])->name('detail.admin');
                // });

                // Riwayat Lembur
                Route::prefix('history')->name('history.')->group(function () {
                    Route::get('/',                           [HistoryAdminController::class, 'index'])->name('index');
                    Route::get('/detail/{id}',                [HistoryAdminController::class, 'showDetail'])->name('detail.admin');
                });

                // Laporan
                Route::prefix('report')->name('report.')->group(function () {
                    Route::get('/',                           [ReportAdminController::class, 'index'])->name('index');
                    Route::get('export',                     [ReportAdminController::class, 'export'])->name('export');
                });
            });


            // ================================
            // ========== ATASAN =============
            // ================================
            Route::prefix('atasan')->middleware('role:atasan')->group(function () {
                Route::get('/',                     [DashboardAtasanController::class, 'index'])->name('atasan.dashboard');

                // Pengajuan Lembur
                Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
                    Route::get('/',                           [OvertimeRequestAtasanController::class, 'index'])->name('index.atasan');
                    Route::get('/get-user-department/{id}',   [OvertimeRequestAtasanController::class, 'getUserDepartment'])->name('get-user-department');
                    Route::post('/create',                    [OvertimeRequestAtasanController::class, 'insertData'])->name('createbyatasan');
                });

                // Approval Lembur
                Route::prefix('approval')->name('approval.')->group(function () {
                    Route::get('/',                           [ApprovalAtasanController::class, 'index'])->name('index');
                    Route::patch('/approve/{id}',             [ApprovalAtasanController::class, 'Approve'])->name('approve');
                    Route::patch('/reject/{id}',              [ApprovalAtasanController::class, 'Reject'])->name('reject');
                    Route::get('/detail/{id}',                [ApprovalAtasanController::class, 'showDetail'])->name('detail.atasan');
                });

                // Riwayat Lembur
                Route::prefix('history')->name('history.')->group(function () {
                    Route::get('/',                           [HistoryAtasanController::class, 'index'])->name('index');
                    Route::get('/detail/{id}',                [HistoryAtasanController::class, 'showDetail'])->name('detail.atasan');
                });
            });


            // ================================
            // ========== PEGAWAI ============
            // ================================
            Route::prefix('pegawai')->middleware('role:pegawai')->group(function () {
                Route::get('/',                     [DashboardPegawaiController::class, 'index'])->name('pegawai.dashboard');

                // Pengajuan Lembur
                Route::prefix('penugasan')->name('penugasan.')->group(function () {
                    Route::get('/',                           [PenugasanLemburController::class, 'index'])->name('index.pegawai');
                    Route::get('/detail/{id}',                [PenugasanLemburController::class, 'showDetail'])->name('detail.pegawai');
                });

                // Riwayat Lembur
                Route::prefix('history')->name('history.')->group(function () {
                    Route::get('/',                           [HistoryPegawaiController::class, 'index'])->name('index');
                    Route::get('/detail/{id}',                [HistoryPegawaiController::class, 'showDetail'])->name('detail.pegawai');
                });
            });
        });
    }
);

// ========================================
//             AUTH ROUTES
// ========================================
require __DIR__ . '/auth.php';

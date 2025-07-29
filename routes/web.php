<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// admin
use App\Http\Controllers\apps\Admin\UserController;
use App\Http\Controllers\apps\Admin\PositionController;
use App\Http\Controllers\apps\Admin\DepartmentController;
use App\Http\Controllers\apps\Admin\HistoryAdminController;
use App\Http\Controllers\apps\Admin\ApprovalAdminController;
use App\Http\Controllers\apps\Admin\DashboardAdminController;
use App\Http\Controllers\apps\Admin\OvertimeRequestAdminController;

// atasan
use App\Http\Controllers\apps\Atasan\DashboardAtasanController;
use App\Http\Controllers\apps\Atasan\ApprovalAtasanController;
use App\Http\Controllers\apps\Atasan\OvertimeRequestAtasanController;
use App\Http\Controllers\apps\Atasan\HistoryAtasanController;

// pegawai
use App\Http\Controllers\apps\Pegawai\DashboardPegawaiController;
use App\Http\Controllers\apps\Pegawai\OvertimeRequestPegawaiController;
use App\Http\Controllers\apps\Pegawai\HistoryPegawaiController;


Route::get('/', function () {
    return view('welcome');
});

// ========================================
//            AUTHENTICATED ROUTES
// ========================================
Route::middleware('auth')->group(
    function () {

        // ========= PROFILE ==========
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // ========= APLIKASI ==========
        Route::prefix('apps')->group(function () {

            // ========= DASHBOARD UTAMA ==========
            Route::get('/', [DashboardAdminController::class, 'index'])->name('index');


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
                Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
                    Route::get('/',                           [OvertimeRequestAdminController::class, 'index'])->name('index');
                    Route::get('/get-user-department/{id}',   [OvertimeRequestAdminController::class, 'getUserDepartment'])->name('get-user-department');
                    Route::post('/create',                    [OvertimeRequestAdminController::class, 'insertData'])->name('create');
                });

                // Approval Lembur
                Route::prefix('approval')->name('approval.')->group(function () {
                    Route::get('/',                           [ApprovalAdminController::class, 'index'])->name('index');
                    Route::patch('/approve/{id}',             [ApprovalAdminController::class, 'Approve'])->name('approve');
                    Route::patch('/reject/{id}',              [ApprovalAdminController::class, 'Reject'])->name('reject');
                    Route::get('/detail/{id}',                [ApprovalAdminController::class, 'showDetail'])->name('detail');
                });

                // Riwayat Lembur
                Route::prefix('history')->name('history.')->group(function () {
                    Route::get('/',                           [HistoryAdminController::class, 'index'])->name('index');
                    Route::get('/detail/{id}',                [HistoryAdminController::class, 'showDetail'])->name('detail');
                });

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
            Route::prefix('atasan')->middleware('role:atasan')->group(function () {
                Route::get('/',                     [DashboardAtasanController::class, 'index'])->name('atasan.dashboard');

                // Pengajuan Lembur
                Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
                    Route::get('/',                           [OvertimeRequestAtasanController::class, 'index'])->name('index');
                    Route::get('/get-user-department/{id}',   [OvertimeRequestAtasanController::class, 'getUserDepartment'])->name('get-user-department');
                    Route::post('/create',                    [OvertimeRequestAtasanController::class, 'insertData'])->name('create');
                });

                // Approval Lembur
                Route::prefix('approval')->name('approval.')->group(function () {
                    Route::get('/',                           [ApprovalAtasanController::class, 'index'])->name('index');
                    Route::patch('/approve/{id}',             [ApprovalAtasanController::class, 'Approve'])->name('approve');
                    Route::patch('/reject/{id}',              [ApprovalAtasanController::class, 'Reject'])->name('reject');
                    Route::get('/detail/{id}',                [ApprovalAtasanController::class, 'showDetail'])->name('detail');
                });

                // Riwayat Lembur
                Route::prefix('history')->name('history.')->group(function () {
                    Route::get('/',                           [HistoryAtasanController::class, 'index'])->name('index');
                    Route::get('/detail/{id}',                [HistoryAtasanController::class, 'showDetail'])->name('detail');
                });
            });


            // ================================
            // ========== PEGAWAI ============
            // ================================
            Route::prefix('pegawai')->middleware('role:pegawai')->group(function () {
                Route::get('/',                     [DashboardPegawaiController::class, 'index'])->name('pegawai.dashboard');

                // Pengajuan Lembur
                Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
                    Route::get('/',                           [OvertimeRequestPegawaiController::class, 'index'])->name('index');
                    Route::get('/get-user-department/{id}',   [OvertimeRequestPegawaiController::class, 'getUserDepartment'])->name('get-user-department');
                    Route::post('/create',                    [OvertimeRequestPegawaiController::class, 'insertData'])->name('create');
                });

                // Riwayat Lembur
                Route::prefix('history')->name('history.')->group(function () {
                    Route::get('/',                           [HistoryPegawaiController::class, 'index'])->name('index');
                    Route::get('/detail/{id}',                [HistoryPegawaiController::class, 'showDetail'])->name('detail');
                });
            });
        });

        Route::get('/redirect-by-role', function () {
            $user = Auth::user();

            // debbud sementara
            logger('Redirect Role', ['role_id' => $user->role_id, 'role' => $user->role]);

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
    }
);

// ========================================
//             AUTH ROUTES
// ========================================
require __DIR__ . '/auth.php';

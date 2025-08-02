<?php

namespace App\Http\Controllers\apps\Atasan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardAtasanController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;


        $user = Auth::user();
        $departmentId = $user->department_id;


        // Ambil data overtime bulan ini
        $dataPengajuan = OvertimeRequest::with(['user', 'department'])
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->latest()
            ->get();


        // Total jumlah pengajuan
        $totalPengajuan = $dataPengajuan->count();

        // Total berdasarkan status
        $totalPending = $dataPengajuan->where('status', 'pending')->count();
        $totalApproved = $dataPengajuan->where('status', 'approved')->count();
        $totalRejected = $dataPengajuan->where('status', 'rejected')->count();
        $testing = 'testing';

        return view('Atasan.dashboard', compact(
            'dataPengajuan',
            'totalPengajuan',
            'totalPending',
            'totalApproved',
            'totalRejected',
        ));
    }
}

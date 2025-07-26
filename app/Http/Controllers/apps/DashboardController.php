<?php

namespace App\Http\Controllers\apps;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Ambil data overtime bulan ini
        $dataPengajuan = OvertimeRequest::with(['user', 'department'])
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

        return view('Admin.dashboard', compact(
            'dataPengajuan',
            'totalPengajuan',
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }
}

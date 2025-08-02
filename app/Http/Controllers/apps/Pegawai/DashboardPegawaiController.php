<?php

namespace App\Http\Controllers\apps\Pegawai;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardPegawaiController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $user = Auth::user();

        // Ambil data overtime bulan ini
        $dataPengajuan = OvertimeRequest::with(['user', 'department'])
            ->where('user_id', $user->id)
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

        return view('Pegawai.dashboard', compact(
            'dataPengajuan',
            'totalPengajuan',
            'totalPending',
            'totalApproved',
            'totalRejected',
        ));
    }
}

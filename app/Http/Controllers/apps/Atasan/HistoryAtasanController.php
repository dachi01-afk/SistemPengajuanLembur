<?php

namespace App\Http\Controllers\apps\Atasan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HistoryAtasanController extends Controller
{
    // Menampilkan halaman histori
    public function index()
    {
        $user = Auth::user();
        $departmentId = $user->department_id;

        $dataPengajuan = OvertimeRequest::with(['user.department', 'approvedby'])
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('Atasan.dataHistory', compact('dataPengajuan'));
    }

    // Menampilkan detail data pengajuan via AJAX
    public function showDetail($id)
    {
        $data = OvertimeRequest::with(['user', 'department', 'approvedby'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $data->user->name ?? '-',
                'departemen' => $data->user->department->department ?? '-',
                'tanggal_pengajuan' => $data->overtime_date,
                'jam' => $data->start_time . ' - ' . $data->end_time,
                'alasan' => $data->reason,
                'status' => $data->status,
                'catatan' => $data->approval_note ?? '-',
                'diproses_oleh' => $data->approvedby->name ?? '-',
                'tanggal_proses' => $data->approved_at ? Carbon::parse($data->approved_at)->format('d-m-Y H:i') : '-',
            ]
        ]);
    }
}

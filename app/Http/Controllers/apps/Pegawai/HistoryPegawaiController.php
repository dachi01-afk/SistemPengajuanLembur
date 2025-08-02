<?php

namespace App\Http\Controllers\apps\Pegawai;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HistoryPegawaiController extends Controller
{
    // Menampilkan halaman histori
    public function index()
    {

        $user = Auth::user();

        $dataPengajuan = OvertimeRequest::with(['user.department', 'approvedby'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('Pegawai.dataHistory', compact('dataPengajuan'));
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

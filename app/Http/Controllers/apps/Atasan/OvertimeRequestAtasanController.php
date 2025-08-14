<?php

namespace App\Http\Controllers\apps\Atasan;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OvertimeRequestAtasanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $department = $user->department;

        $employees = User::where('department_id', $user->department_id)
            ->where('id', '!=', $user->id)
            ->get();

        return view('Atasan.overtimeRequest', compact('user', 'department', 'employees'));
    }


    public function insertData(Request $request)
    {
        $user = Auth::user();
        // try {

        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'required|string|max:1000',
            'pegawai_id' => 'required|exists:users,id',
            'spt_file' => 'required|mimes:pdf|max:2048',
        ]);

        // Simpan file SPT
        $sptPath = $request->file('spt_file')->store('spt_files', 'public');
        // Konversi ke format TIME MySQL
        $start = date('H:i:s', strtotime($request->jam_mulai));
        $end = date('H:i:s', strtotime($request->jam_selesai));

        OvertimeRequest::create([
            'user_id' => $request->pegawai_id,
            'department_id' => $user->department_id,
            'overtime_date' => $request->tanggal,
            'start_time' => $request->jam_mulai,
            'end_time' => $request->jam_selesai,
            'reason' => $request->keterangan,
            'spt_file' => $sptPath,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Pengajuan lembur berhasil di ajukan!']);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }
}

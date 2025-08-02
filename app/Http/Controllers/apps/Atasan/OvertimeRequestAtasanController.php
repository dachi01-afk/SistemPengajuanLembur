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
        return view('Atasan.overtimeRequest', compact('user', 'department'));
    }


    public function insertData(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'tanggal'       => 'required|date|after_or_equal:today',
            'jam_mulai'     => 'required|date_format:H:i',
            'jam_selesai'   => 'required|date_format:H:i|after:jam_mulai',
            'keterangan'    => 'required|string|max:1000',
        ]);

        // try {
        OvertimeRequest::create([
            'user_id'       => $user->id,
            'department_id' => $user->department_id,
            'overtime_date' => $request->tanggal,
            'start_time'    => $request->jam_mulai,
            'end_time'      => $request->jam_selesai,
            'reason'        => $request->keterangan,
            'status'        => 'pending',
        ]);

        return response()->json(['message' => 'Pengajuan lembur berhasil di ajukan!']);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }
}

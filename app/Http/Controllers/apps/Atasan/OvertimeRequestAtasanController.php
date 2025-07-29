<?php

namespace App\Http\Controllers\apps\Atasan;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use App\Models\User;

use Illuminate\Http\Request;

class OvertimeRequestAtasanController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('Atasan.overtimeRequest', compact('users'));
    }

    public function getUserDepartment($id)
    {
        $user = User::with('department')->find($id);

        if (!$user || !$user->department) {
            return response()->json(['department_id' => null, 'department_name' => null]);
        }

        return response()->json([
            'department_id' => $user->department->id,
            'department_name' => $user->department->department,
        ]);
    }

    public function insertData(Request $request)
    {

        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'tanggal'       => 'required|date|after_or_equal:today',
            'jam_mulai'     => 'required|date_format:H:i',
            'jam_selesai'   => 'required|date_format:H:i|after:jam_mulai',
            'keterangan'    => 'required|string|max:1000',
        ]);

        // try {
        OvertimeRequest::create([
            'user_id'       => $request->user_id,
            'department_id' => $request->department_id,
            'overtime_date' => $request->tanggal,
            'start_time'    => $request->jam_mulai,
            'end_time'      => $request->jam_selesai,
            'reason'        => $request->keterangan,
            'status'        => 'pending',
        ]);

        return response()->json(['message' => 'Pengajuan lembur berhasil disimpan.']);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }
}

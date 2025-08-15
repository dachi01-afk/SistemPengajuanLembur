<?php

namespace App\Http\Controllers\apps\Atasan;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Illuminate\Support\Facades\DB;        // <— tambahkan
use Illuminate\Support\Facades\Storage; 


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
            ->orderBy('name')
            ->get();

        return view('Atasan.overtimeRequest', compact('user', 'department', 'employees'));
    }

    public function insertData(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'pegawai_id'   => 'required|array|min:1',
            'pegawai_id.*' => 'required|exists:users,id',
            'tanggal'      => 'required|date|after_or_equal:today',
            'jam_mulai'    => 'required|date_format:H:i',
            'jam_selesai'  => 'required|date_format:H:i|after:jam_mulai',
            'keterangan'   => 'required|string|max:1000',
            'spt_number'   => 'required|string|max:100',
            'spt_file'     => 'required|mimes:pdf|max:2048',
        ]);

        // simpan file SPT sekali untuk semua record
        $sptPath = $request->file('spt_file')->store('spt_files', 'public');

        $start = date('H:i:s', strtotime($validated['jam_mulai']));
        $end   = date('H:i:s', strtotime($validated['jam_selesai']));
        $pegawaiIds = array_unique($validated['pegawai_id']);

        DB::beginTransaction();
        try {
            foreach ($pegawaiIds as $pegawaiId) {
                OvertimeRequest::create([
                    'user_id'       => $pegawaiId,
                    'department_id' => $user->department_id,
                    'overtime_date' => $validated['tanggal'],
                    'start_time'    => $start,
                    'end_time'      => $end,
                    'reason'        => $validated['keterangan'],
                    'spt_number'    => $validated['spt_number'],
                    'spt_file'      => $sptPath,
                    'status'        => 'pending',
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'Pengajuan lembur berhasil diajukan untuk '.count($pegawaiIds).' pegawai!'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($sptPath && Storage::disk('public')->exists($sptPath)) {
                Storage::disk('public')->delete($sptPath);
            }
            return response()->json([
                'error' => 'Gagal menyimpan pengajuan: '.$e->getMessage()
            ], 500);
        }
    }
}
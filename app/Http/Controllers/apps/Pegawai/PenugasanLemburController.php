<?php

namespace App\Http\Controllers\apps\Pegawai;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Models\OvertimeFeedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PenugasanLemburController extends Controller
{
    // Menampilkan halaman histori
    public function index()
    {

        $user = Auth::user();

        $dataPenugasan = OvertimeRequest::with(['user.department', 'approvedby'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('Pegawai.dataPenugasanLembur', compact('dataPenugasan'));
    }

    public function showDetail($id)
    {
        $data = OvertimeRequest::with(['user', 'department'])
            ->findOrFail($id);

        $spt_url = null;
        if (!empty($data->spt_file) && Storage::disk('public')->exists($data->spt_file)) {
            $spt_url = Storage::url($data->spt_file); // ini hasilnya /storage/spt_files/...
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $data->user->name ?? '-',
                'departemen' => $data->user->department->department ?? '-',
                'tanggal_pengajuan' => $data->overtime_date,
                'jam' => $data->start_time . ' - ' . $data->end_time,
                'alasan' => $data->reason,
                'status' => $data->status,
                'spt_url' => $spt_url
                // 'catatan' => $data->approval_note ?? '-',
                // 'diproses_oleh' => $data->approvedby->approved_by ?? '-',
                // 'tanggal_proses' => $data->approved_at ? Carbon::parse($data->approved_at)->format('d-m-Y H:i') : '-',
            ]
        ]);
    }

    public function insertFeedback(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'overtime_request_id' => 'required|exists:overtime_requests,id',
            'activity_description' => 'required|string',
            'documentation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $filePath = null;
        if ($request->hasFile('documentation')) {
            $filePath = $request->file('documentation')->store('overtime_docs', 'public');
        }

        $feedback = OvertimeFeedback::create([
            'overtime_request_id' => $request->overtime_request_id,
            'user_id' => auth()->id(),
            'activity_description' => $request->activity_description,
            'documentation' => $filePath
        ]);

        // Update status feedback_submitted
        OvertimeRequest::where('id', $request->overtime_request_id)
            ->update(['feedback_submitted' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback lembur berhasil disimpan',
            'data' => $feedback
        ]);
    }
}

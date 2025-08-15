<?php

namespace App\Http\Controllers\apps\Atasan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Models\OvertimeFeedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ApprovalAtasanController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $departmentId = $user->department_id;

        $requests = OvertimeRequest::with(['user.department'])
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->where('status', 'pending')
            ->get();

        return view('Atasan.dataApproval', compact('requests'));
    }

    public function Approve($id)
    {
        $request = OvertimeRequest::findOrFail($id);
        $request->status = 'approved';
        $request->approved_by = auth()->id();
        $request->approved_at = now();
        $request->save();

        return response()->json(['success' => true, 'message' => 'Pengajuan berhasil disetujui.']);
    }

    public function Reject(Request $request, $id)
    {
        $overtime = OvertimeRequest::findOrFail($id);
        $overtime->status = 'rejected';
        $overtime->approved_by = auth()->id();
        $overtime->approved_at = now();
        $overtime->approval_note = $request->input('approval_note');
        $overtime->save();

        return response()->json(['success' => true, 'message' => 'Pengajuan ditolak.']);
    }

    public function showDetail($id)
    {
        $data = OvertimeRequest::with(['user', 'department', 'approvedby'])
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

    public function showdataFeedback($id)
    {
        // Ambil feedback berdasarkan id pengajuan lembur
        $feedback = OvertimeFeedback::where('overtime_request_id', $id)->first();

        if (!$feedback) {
            return response()->json([
                'status' => false,
                'message' => 'Data feedback tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'activity_description' => $feedback->activity_description,
                'documentation_url'    => $feedback->documentation
                    ? asset('storage/' . $feedback->documentation)
                    : null
            ]
        ]);
    }
}

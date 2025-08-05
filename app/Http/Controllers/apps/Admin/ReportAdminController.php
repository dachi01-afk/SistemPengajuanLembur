<?php

namespace App\Http\Controllers\apps\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Exports\OvertimeExport;



class ReportAdminController extends Controller
{

    public function index(Request $request)
    {
        $query = OvertimeRequest::with(['user', 'department']);
        // ->whereIn('status', ['approved', 'rejected']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('overtime_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dataPengajuan = $query->get();

        $summary = [
            'total' => $dataPengajuan->count(),
            'approved' => $dataPengajuan->where('status', 'approved')->count(),
            'rejected' => $dataPengajuan->where('status', 'rejected')->count(),
            'pending' => $dataPengajuan->where('status', 'pending')->count(),
        ];

        return view('Admin.dataReport', compact('dataPengajuan', 'summary'));
    }


    public function export(Request $request)
    {
        $format = $request->format;

        $data = OvertimeRequest::with('user', 'department')
            ->when($request->start_date, fn($q) => $q->whereDate('overtime_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('overtime_date', '<=', $request->end_date))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        if ($format === 'excel') {
            return Excel::download(new OvertimeExport($data), 'laporan_lembur.xlsx');
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('Admin.Laporan.export-pdf', compact('data'));
            return $pdf->download('laporan_lembur.pdf');
        }

        return back();
    }
}

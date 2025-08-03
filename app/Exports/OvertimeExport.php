<?php

namespace App\Exports;

use App\Models\OvertimeRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OvertimeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return OvertimeRequest::with(['user', 'department'])
            ->get();
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->user->name ?? '-',
            $item->department->department ?? '-',
            \Carbon\Carbon::parse($item->start_time)->format('H:i'),
            \Carbon\Carbon::parse($item->end_time)->format('H:i'),
            $item->reason ?? '-',
            ucfirst($item->status),
            \Carbon\Carbon::parse($item->created_at)->format('Y-m-d'),
        ];
    }


    public function headings(): array
    {
        return [
            'ID',
            'Nama Pegawai',
            'Departemen',
            'Jam Mulai',
            'Jam Selesai',
            'Alasan Lembur',
            'Status',
            'Tanggal Pengajuan',
        ];
    }
}

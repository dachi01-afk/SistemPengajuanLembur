<!DOCTYPE html>
<html>

<head>
    <title>Laporan Lembur</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h2>Laporan Pengajuan Lembur</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->department->department ?? '-' }}</td>
                    <td>{{ $item->overtime_date }}</td>
                    <td>{{ $item->start_time }} - {{ $item->end_time }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

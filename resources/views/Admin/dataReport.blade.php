 @extends('Admin.main')
 @section('content_admin')
     <main class="h-full overflow-y-auto">
         <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">


             <h2 class="my-6 text-2xl font-semibold text-gray-700">Laporan Pengajuan Lembur</h2>

             {{-- Ringkasan Box --}}

             <div class="grid gap-6 mb-6 md:grid-cols-2 xl:grid-cols-4">
                 <x-summary-box icon="briefcase" color="blue" label="Total Pengajuan" :count="$summary['total'] ?? 0" />
                 <x-summary-box icon="check" color="green" label="Disetujui" :count="$summary['approved'] ?? 0" />
                 <x-summary-box icon="xmark" color="red" label="Ditolak" :count="$summary['rejected'] ?? 0" />
                 <x-summary-box icon="clock" color="yellow" label="Pending" :count="$summary['pending'] ?? 0" />
             </div>

             {{-- Export + Table --}}
             <div class="bg-white rounded-lg shadow p-4">
                 {{-- Export Button --}}
                 <div class="flex justify-between mb-4 items-center">
                     <div class="space-x-2 grid gap-6  md:grid-cols-2 ">
                         <a href="{{ route('report.export', [
                             'format' => 'excel',
                             'start_date' => request('start_date'),
                             'end_date' => request('end_date'),
                             'status' => request('status'),
                         ]) }}"
                             class="btn btn-success px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                             Export Excel
                         </a>
                         <a href="{{ route('report.export', [
                             'format' => 'pdf',
                             'start_date' => request('start_date'),
                             'end_date' => request('end_date'),
                             'status' => request('status'),
                         ]) }}"
                             class="btn btn-danger px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                             Export PDF
                         </a>
                         {{-- <a href="{{ route('report.export', ['format' => 'pdf']) }}?{{ $query }}"
                             class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                             Export PDF
                         </a> --}}
                     </div>
                     {{-- Filter --}}
                     <form method="GET" action="" class="flex flex-wrap gap-2">
                         <input type="date" name="start_date" value="{{ request('start_date') }}"
                             class="border rounded px-3 py-1 text-sm" placeholder="Start Date" />
                         <input type="date" name="end_date" value="{{ request('end_date') }}"
                             class="border rounded px-3 py-1 text-sm" placeholder="End Date" />
                         <select name="status" class="border rounded px-3 py-1 text-sm">
                             <option value="">Semua</option>
                             <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                             <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                             <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                         </select>
                         <button type="submit"
                             class="px-4 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">
                             Filter
                         </button>
                     </form>
                 </div>

                 {{-- Table --}}
                 <div class="w-full overflow-hidden rounded-lg shadow-xs">
                     <div class="w-full overflow-x-auto">
                         <table class="w-full whitespace-no-wrap">
                             <thead>
                                 <tr
                                     class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                     <th class="px-4 py-3">Nama</th>
                                     <th class="px-4 py-3">Departemen</th>
                                     <th class="px-4 py-3">Tanggal</th>
                                     <th class="px-4 py-3">Jam</th>
                                     <th class="px-4 py-3">Durasi</th>
                                     <th class="px-4 py-3">Status</th>
                                 </tr>
                             </thead>
                             <tbody class="bg-white divide-y">
                                 @forelse ($dataPengajuan as $pengajuan)
                                     @php
                                         $durasi = \Carbon\Carbon::parse($pengajuan->start_time)->diffInMinutes(
                                             \Carbon\Carbon::parse($pengajuan->end_time),
                                         );
                                     @endphp
                                     <tr class="text-gray-700">
                                         <td class="px-4 py-3 text-sm">{{ $pengajuan->user->name ?? '-' }}</td>
                                         <td class="px-4 py-3 text-sm">{{ $pengajuan->department->department ?? '-' }}</td>
                                         <td class="px-4 py-3 text-sm">{{ $pengajuan->overtime_date }}</td>
                                         <td class="px-4 py-3 text-sm">{{ $pengajuan->start_time }} -
                                             {{ $pengajuan->end_time }}
                                         </td>
                                         <td class="px-4 py-3 text-sm">{{ floor($durasi / 60) }}j {{ $durasi % 60 }}m
                                         </td>
                                         <td class="px-4 py-3 text-sm">
                                             @php
                                                 $status = $pengajuan->status;
                                                 $color = match ($status) {
                                                     'approved' => 'green',
                                                     'rejected' => 'red',
                                                     default => 'yellow',
                                                 };
                                             @endphp
                                             <span
                                                 class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                                                 {{ ucfirst($status) }}
                                             </span>
                                         </td>
                                     </tr>
                                 @empty
                                     <tr>
                                         <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data
                                             ditemukan.</td>
                                     </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>

         </div>
     </main>
 @endsection

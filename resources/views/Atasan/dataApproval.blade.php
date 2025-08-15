@extends('Atasan.main')

@section('content_atasan')
<main class="h-full overflow-y-auto" x-data="{ isDetailModalOpen: false, isFeedbackModalOpen: false }">
    <style>[x-cloak]{ display:none !important; }</style>

    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Daftar Data Pending</h2>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table id="dataTable" class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 uppercase border-b bg-gray-50">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Departemen</th>
                            <th class="px-4 py-3">Tanggal Pengajuan</th>
                            <th class="px-4 py-3">Jam</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Detail</th>
                            <th class="px-4 py-3 text-center">Res Pegawai</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y">
                        @forelse ($groupedRequests as $sptNumber => $items)
                            {{-- Header Grup SPT --}}
                            <tr class="bg-indigo-50/60 border-t">
                                <td class="px-4 py-2 text-sm font-semibold text-indigo-900" colspan="5">
                                    Nomor SPT: {{ $sptNumber ?: '-' }}
                                    <span class="ml-2 text-gray-500 font-normal">
                                        ({{ $items->count() }} pegawai)
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-center" colspan="3">
                                    @php
                                        $first = $items->first();
                                        $sptUrl = ($first && $first->spt_file && Storage::disk('public')->exists($first->spt_file))
                                            ? Storage::url($first->spt_file)
                                            : null;
                                    @endphp
                                    @if ($sptUrl)
                                        <button onclick="window.open('{{ $sptUrl }}','_blank')"
                                                class="inline-flex items-center gap-2 px-3 py-1 text-sm text-white bg-purple-600 rounded hover:bg-purple-700 transition">
                                            <i class="fa-solid fa-file-lines text-base"></i>
                                            Preview SPT
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">SPT tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Item per pegawai dalam grup --}}
                            @foreach ($items as $request)
                                <tr class="text-gray-700">
                                    <td class="px-4 py-3 text-sm">
                                        {{ $request->user->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $request->department->department ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $request->overtime_date }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $request->start_time }} - {{ $request->end_time }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        @if ($request->status == 'pending')
                                            <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Approved</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Rejected</span>
                                        @endif
                                    </td>

                                    {{-- Detail --}}
                                    <td class="px-4 py-3 text-sm text-center align-middle">
                                        <div class="flex justify-center items-center">
                                            <button
                                                @click="isDetailModalOpen = true"
                                                data-id="{{ $request->id }}"
                                                class="btn-detail inline-flex items-center justify-center rounded-md p-2 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300 text-blue-600 hover:text-blue-800 transition"
                                                title="Detail Pengajuan"
                                                aria-label="Detail Pengajuan">
                                                <i class="fas fa-eye text-lg"></i>
                                            </button>
                                        </div>
                                    </td>

                                    {{-- Res Pegawai --}}
                                    <td class="px-4 py-3 text-sm text-center">
                                        @if ($request->feedback_submitted)
                                            <button
                                                @click="isFeedbackModalOpen = true"
                                                class="btn-detailfeedback inline-flex items-center justify-center rounded-md p-2 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-purple-600 hover:text-purple-800 transition"
                                                title="Lihat Res Pegawai"
                                                data-id="{{ $request->id }}"
                                                aria-label="Lihat Res Pegawai">
                                                <i class="fa-solid fa-user-check text-lg"></i>
                                            </button>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                                                Belum Dilaksanakan
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex justify-center items-center space-x-2">
                                            <button
                                                onclick="approveRequest({{ $request->id }})"
                                                class="approve-btn inline-flex items-center justify-center rounded-md p-2 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-600 hover:text-green-800 transition"
                                                title="Setujui"
                                                aria-label="Setujui">
                                                <i class="fas fa-check-circle text-lg"></i>
                                            </button>

                                            <button
                                                onclick="rejectRequest({{ $request->id }})"
                                                class="reject-btn inline-flex items-center justify-center rounded-md p-2 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300 text-red-600 hover:text-red-800 transition"
                                                title="Tolak"
                                                aria-label="Tolak">
                                                <i class="fas fa-times-circle text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    Tidak ada data pending.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div
        x-cloak
        x-show="isDetailModalOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 flex items-end bg-black/50 sm:items-center sm:justify-center">

        <div
            x-show="isDetailModalOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1/2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1/2"
            @click.away="isDetailModalOpen = false"
            @keydown.escape.window="isDetailModalOpen = false"
            class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
            role="dialog" aria-modal="true" aria-labelledby="modalTitleDetail">

            <header class="flex justify-between items-center mb-4">
                <h2 id="modalTitleDetail" class="text-lg font-semibold text-gray-700">Detail Data</h2>
                <button type="button" @click="isDetailModalOpen = false"
                    class="text-gray-500 hover:text-gray-800 text-xl font-bold" aria-label="Tutup">&times;</button>
            </header>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                    <tbody>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium w-1/3 uppercase">Nama</th>
                            <td class="py-3 px-4" id="detail-nama">-</td>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium uppercase">Departemen</th>
                            <td class="py-3 px-4" id="detail-departemen">-</td>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium uppercase">Tanggal Pengajuan</th>
                            <td class="py-3 px-4" id="detail-tanggal">-</td>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium uppercase">Jam</th>
                            <td class="py-3 px-4" id="detail-jam">-</td>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium uppercase">Alasan</th>
                            <td class="py-3 px-4" id="detail-alasan">-</td>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium uppercase">Status</th>
                            <td class="py-3 px-4">
                                <span id="detail-status" class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800">-</span>
                            </td>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium uppercase">File SPT</th>
                            <td class="py-3 px-4">
                                <button id="btn-preview-spt"
                                    class="inline-flex items-center gap-2 px-3 py-1 text-sm text-white bg-purple-600 rounded hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled
                                    title="Preview SPT">
                                    <i class="fa-solid fa-file-lines text-base"></i>
                                    Preview
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Modal Detail Feedback --}}
    <div
        x-cloak
        x-show="isFeedbackModalOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 flex items-end bg-black/50 sm:items-center sm:justify-center">

        <div
            x-show="isFeedbackModalOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1/2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1/2"
            @click.away="isFeedbackModalOpen = false"
            @keydown.escape.window="isFeedbackModalOpen = false"
            class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
            role="dialog" aria-modal="true" aria-labelledby="modalTitleFeedback">

            <header class="flex justify-between items-center mb-4">
                <h2 id="modalTitleFeedback" class="text-lg font-semibold text-gray-700">Detail Feedback</h2>
                <button type="button" @click="isFeedbackModalOpen = false"
                    class="text-gray-500 hover:text-gray-800 text-xl font-bold" aria-label="Tutup">&times;</button>
            </header>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                    <tbody>
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium w-1/3">Deskripsi Kegiatan</th>
                            <td class="py-3 px-4" id="detail-deskripsi">-</td>
                        </tr>
                        <tr class="bg-white">
                            <th class="py-3 px-4 text-left font-medium">Dokumentasi</th>
                            <td class="py-3 px-4">
                                <button id="btn-preview-doc"
                                    class="inline-flex items-center gap-2 px-3 py-1 text-sm text-white bg-purple-600 rounded hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled
                                    title="Preview Dokumentasi">
                                    <i class="fa-solid fa-image text-base"></i>
                                    Preview
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</main>

{{-- Script --}}
<script>
    function setStatusBadge(status) {
        const statusSpan = document.getElementById('detail-status');
        statusSpan.className = "inline-block px-2 py-1 rounded-full text-xs font-semibold";

        switch ((status || '').toString().toLowerCase()) {
            case 'approved':
                statusSpan.classList.add('text-green-700', 'bg-green-100', 'dark:bg-green-700', 'dark:text-green-100');
                break;
            case 'rejected':
                statusSpan.classList.add('text-red-700', 'bg-red-100', 'dark:text-red-100', 'dark:bg-red-700');
                break;
            default:
                statusSpan.classList.add('text-orange-700', 'bg-orange-100', 'dark:text-white', 'dark:bg-orange-600');
        }

        statusSpan.textContent = (status || '-').toString().toUpperCase();
    }

    // Approve
    function approveRequest(id) {
        Swal.fire({
            title: 'Setujui Pengajuan?',
            text: "Data ini akan disetujui!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Setujui!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('apps/atasan/approval/approve') }}/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Server error or route not found');
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('Disetujui!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyetujui.', 'error');
                });
            }
        });
    }

    // Reject
    function rejectRequest(id) {
        Swal.fire({
            title: 'Tolak Pengajuan?',
            input: 'textarea',
            inputLabel: 'Catatan Penolakan',
            inputPlaceholder: 'Masukkan alasan penolakan...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) return 'Catatan harus diisi!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('apps/atasan/approval/reject') }}/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ approval_note: result.value })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Ditolak!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(() => Swal.fire('Gagal', 'Terjadi kesalahan saat menolak.', 'error'));
            }
        });
    }

    // Detail data (modal Detail)
    $(document).on('click', '.btn-detail', function () {
        const id = $(this).data('id');

        $.get('{{ route('approval.detail.atasan', ':id') }}'.replace(':id', id), function (res) {
            const data = res.data || {};
            $('#detail-nama').text(data.nama || '-');
            $('#detail-departemen').text(data.departemen || '-');
            $('#detail-tanggal').text(data.tanggal_pengajuan || '-');
            $('#detail-jam').text(data.jam || '-');
            $('#detail-alasan').text(data.alasan || '-');
            setStatusBadge(data.status);

            const sptBtn = $('#btn-preview-spt');
            if (data.spt_url) {
                sptBtn.data('url', data.spt_url).prop('disabled', false);
            } else {
                sptBtn.data('url', '').prop('disabled', true);
            }
        });
    });

    // Detail data konfirmasi (modal Feedback)
    $(document).on('click', '.btn-detailfeedback', function () {
        const id = $(this).data('id');

        $.get('{{ route('approval.detailfeedback', ':id') }}'.replace(':id', id), function (res) {
            const data = res.data || {};
            $('#detail-deskripsi').text(data.activity_description || '-');

            const previewBtn = $('#btn-preview-doc');
            if (data.documentation_url) {
                previewBtn.data('url', data.documentation_url).prop('disabled', false);
            } else {
                previewBtn.data('url', '').prop('disabled', true);
            }
        });
    });

    // Preview dokumen feedback
    $(document).on('click', '#btn-preview-doc', function () {
        const url = $(this).data('url');
        if (url) window.open(url, '_blank');
    });

    // Preview SPT (dari modal detail)
    $(document).on('click', '#btn-preview-spt', function () {
        const url = $(this).data('url');
        if (url) window.open(url, '_blank');
    });
</script>
@endsection

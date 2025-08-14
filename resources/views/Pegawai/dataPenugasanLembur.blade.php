@extends('Pegawai.main')
@section('content_pegawai')
    <main class="h-full overflow-y-auto">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid" x-data="{ isDetailModalOpen: false, isFeedbackModalOpen: false }">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Data Penugasan Lembur</h2>

            <!-- New Table -->
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    <table id="dataTable" class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Departemen</th>
                                <th class="px-4 py-3">Tanggal Pengajuan</th>
                                <th class="px-4 py-3">Jam </th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Detail</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach ($dataPenugasan as $dpl)
                                <tr class="text-gray-700">
                                    <td class="px-4 py-3 text-sm">{{ $dpl->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $dpl->department->department ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $dpl->overtime_date }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $dpl->start_time }} - {{ $dpl->end_time }}
                                    <td class="px-4 py-3 text-xs">
                                        @if ($dpl->status == 'pending')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full">Pending</span>
                                        @elseif($dpl->status == 'approved')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Approved</span>
                                        @elseif($dpl->status == 'rejected')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Rejected</span>
                                        @endif
                                    </td>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button @click="isDetailModalOpen = true" data-id="{{ $dpl->id }}"
                                            class="btn-detail text-blue-600 hover:text-blue-900" title="Detail Pengajuan">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <button @click="isFeedbackModalOpen = true"
                                            class="text-purple-600 hover:text-purple-800 focus:outline-none btnFeedback"
                                            data-id="' . $user->id . '">
                                            <i class="fa-solid fa-paper-plane fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal detail data -->

            <div x-cloak x-show="isDetailModalOpen" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">

                <div x-show="isDetailModalOpen" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform translate-y-1/2" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0 transform translate-y-1/2" @click.away="isDetailModalOpen = false"
                    @keydown.escape.window="isDetailModalOpen = false"
                    class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
                    role="dialog" aria-modal="true" aria-labelledby="modalTitle">

                    <header class="flex justify-between items-center mb-4">
                        <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Detail Data</h2>
                        <button type="button" @click="isDetailModalOpen = false"
                            class="text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>
                    </header>

                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                            <tbody>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium w-1/3 uppercase">Nama</th>
                                    <td class="py-3 px-4" id="detail-nama">-</td>
                                </tr>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium uppercase">Departemen</th>
                                    <td class="py-3 px-4" id="detail-departemen">-</td>
                                </tr>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium uppercase">Tanggal Pengajuan</th>
                                    <td class="py-3 px-4" id="detail-tanggal">-</td>
                                </tr>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium uppercase">Jam</th>
                                    <td class="py-3 px-4" id="detail-jam">-</td>
                                </tr>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium uppercase">Alasan</th>
                                    <td class="py-3 px-4" id="detail-alasan">-</td>
                                </tr>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium uppercase">Status</th>
                                    <td class="py-3 px-4">
                                        <span id="detail-status"
                                            class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-gray-300 text-gray-800">-</span>
                                    </td>
                                </tr>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-gray-50">
                                    <th class="py-3 px-4 text-left font-medium uppercase">File SPT</th>
                                    <td class="py-3 px-4">
                                        <button id="btn-preview-spt"
                                            class="px-3 py-1 text-white bg-purple-600 rounded hover:bg-purple-700 transition"
                                            disabled>
                                            Preview
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


            <!-- Modal form feed back -->
            <div x-cloak x-show="isFeedbackModalOpen" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">

                <div x-show="isFeedbackModalOpen" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform translate-y-1/2" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0 transform translate-y-1/2" @click.away="isFeedbackModalOpen = false"
                    @keydown.escape.window="isFeedbackModalOpen = false"
                    class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
                    role="dialog" aria-modal="true" aria-labelledby="modalTitle">

                    <header class="flex justify-between items-center mb-4">
                        <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Konfirmasi Selesai</h2>
                        <button type="button" @click="isFeedbackModalOpen = false"
                            class="text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>
                    </header>

                    <!-- Form -->
                    <form id="formFeedback" class="space-y-4">
                        @csrf
                        <input type="hidden" name="overtime_request_id" id="feedback_overtime_id">
                        

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Aktivitas</label>
                            <textarea name="activity_description" id="activity_description" class="w-full border rounded p-2"
                                placeholder="Tuliskan aktivitas yang dilakukan..." rows="4" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dokumentasi</label>
                            <input type="file" name="documentation" id="documentation"
                                class="w-full border rounded p-2" accept="image/*,application/pdf">
                        </div>

                        <footer class="flex justify-end space-x-3">
                            <button type="button" @click="isFeedbackModalOpen = false"
                                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-purple-600 rounded-md hover:bg-purple-700">
                                Simpan
                            </button>
                        </footer>
                    </form>

                </div>
            </div>
        </div>




    </main>

    <script>
        function setStatusBadge(status) {
            const statusSpan = document.getElementById('detail-status');
            statusSpan.className = "inline-block px-2 py-1 rounded-full text-xs font-semibold";

            switch (status.toLowerCase()) {
                case 'approved':
                    statusSpan.classList.add('px-2', 'py-1', 'font-semibold', 'leading-tight', 'text-green-700',
                        'bg-green-100', 'rounded-full', 'dark:bg-green-700', 'dark:text-green-100');
                    break;
                case 'rejected':
                    statusSpan.classList.add('px-2', 'py-1', 'font-semibold', 'leading-tight', 'text-red-700', 'bg-red-100',
                        'rounded-full', 'dark:text-red-100', 'dark:bg-red-700');
                    break;
                default:
                    statusSpan.classList.add('px-2', 'py-1', 'font-semibold', 'leading-tight', 'text-orange-700',
                        'bg-orange-100', 'rounded-full', 'dark:text-white', 'dark:bg-orange-600');
            }

            statusSpan.textContent = status.toUpperCase();
        }

        // detail data
        $(document).on('click', '.btn-detail', function() {
            const id = $(this).data('id');

            $.get('{{ route('penugasan.detail.pegawai', ':id') }}'.replace(':id', id), function(res) {
                const data = res.data;
                $('#detail-nama').text(data.nama);
                $('#detail-departemen').text(data.departemen);
                $('#detail-tanggal').text(data.tanggal_pengajuan);
                $('#detail-jam').text(data.jam);
                $('#detail-alasan').text(data.alasan);
                setStatusBadge(data.status);

                // simpan url SPT ke tombol preview
                const previewBtn = $('#btn-preview-spt');
                if (data.spt_url) {
                    previewBtn.data('url', data.spt_url).prop('disabled', false);
                } else {
                    previewBtn.data('url', '').prop('disabled', true);
                }
            });
        });

        // Klik tombol preview
        $(document).on('click', '#btn-preview-spt', function() {
            const url = $(this).data('url');
            if (url) {
                window.open(url, '_blank');
            }
        });

           $('#formFeedback').on('submit', function(e) {
            console.log('button submit berhasil di klik')
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('penugasan.feedback') }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.error || 'Terjadi kesalahan',
                        });
                    }
                });
            });
    </script>
@endsection

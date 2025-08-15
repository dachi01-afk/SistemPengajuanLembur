@extends('Atasan.main')
@section('content_atasan')
    <main class="h-full overflow-y-auto">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Pengajuan Lembur Baru</h2>

            <form id="formLembur" action="{{ route('pengajuan.createbyatasan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
                    <!-- Nama Pegawai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pegawai</label>
                        <select name="pegawai_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nip }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Departemen</label>
                        <input type="text" id="department_name"
                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            readonly value="{{ $department->department }}">
                    </div>

                    <!-- Tanggal Lembur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lembur</label>
                        <input type="date" name="tanggal"
                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Jam Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Mulai</label>
                            <input type="time" name="jam_mulai"
                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                required>
                        </div>

                        <!-- Jam Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Jam Selesai</label>
                            <input type="time" name="jam_selesai"
                                class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                required>
                        </div>

                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="4"
                            class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            required placeholder="Contoh: Menyelesaikan laporan bulanan..."></textarea>
                    </div>

                    <!-- Upload SPT -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Upload SPT (PDF)</label>
                        <input type="file" name="spt_file" accept="application/pdf"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg" required>

                        <div class="mt-2 flex gap-2">
                            <button type="button" id="btnPreviewPDF"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hidden">
                                Preview PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-6 text-right">
                    <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200 ease-in-out">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

    </main>

    <script>
        $(document).ready(function() {

            // console.log("Form lembur siap");


            let pdfBlobUrl = '';

            // Saat file diubah
            $('input[name="spt_file"]').on('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type === "application/pdf") {
                    pdfBlobUrl = URL.createObjectURL(file);
                    $('#btnPreviewPDF').removeClass('hidden');
                } else {
                    pdfBlobUrl = '';
                    $('#btnPreviewPDF').addClass('hidden');
                }
            });

            // Saat tombol preview di klik
            $('#btnPreviewPDF').on('click', function() {
                if (pdfBlobUrl) {
                    window.open(pdfBlobUrl, '_blank');
                }
            });



            $('#formLembur').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                // for (var pair of formData.entries()) {
                //     console.log(pair[0] + ': ' + pair[1]);
                // }

                $.ajax({
                    url: '{{ route('pengajuan.createbyatasan') }}',
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
                            window.location.href =
                                '{{ route('pengajuan.index.atasan') }}';
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
        });
    </script>
@endsection

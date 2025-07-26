@extends('Admin.main')
@section('content_admin')

<main class="h-full overflow-y-auto"> 
   
    <div class="p-6 pb-8 bg-white dark:bg-gray-800 rounded-xl shadow-md w-full max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-6">Pengajuan Lembur Baru</h2>
    
    <form id="formLembur">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
            <!-- Nama Pegawai -->
             <div>
                <label class="block text-gray-700 dark:text-gray-200 mb-1">Nama Pegawai</label>
                <select name="user_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nip }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Department -->
            <div>
                <label class="block text-gray-700 dark:text-gray-200 mb-1">Departemen</label>
                <input type="text" id="department_name" class="w-full px-3 py-2 border rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300" readonly>
                <input type="hidden" name="department_id" id="department_id">
            </div>

            <!-- Tanggal Lembur -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Lembur</label>
                <input type="date" name="tanggal"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Jam Mulai -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            </div>

            <!-- Jam Selesai -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
            </div>

            </div>

            <!-- Keterangan -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="4"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required
                    placeholder="Contoh: Menyelesaikan laporan bulanan..."></textarea>
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
    $(document).ready(function () {

        // console.log("Form lembur siap");

    
  $('select[name="user_id"]').on('change', function () {
    const userId = $(this).val();

    if (userId) {
        $.get('pengajuan/get-user-department/' + userId, function (data) {

            if (data.department_id) {
                $('#department_id').val(data.department_id);
                $('#department_name').val(data.department_name);
            } else {
                $('#department_id').val('');
                $('#department_name').val('');
            }
        });
    } else {
        $('#department_id').val('');
        $('#department_name').val('');
    }
 });



    $('#formLembur').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("pengajuan.create") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pengajuan lembur berhasil di ajukan!',
                }).then(() => {
                    window.location.href = '{{ route("pengajuan.index") }}';
                });
            }
        });
    });
});
</script>
@endsection
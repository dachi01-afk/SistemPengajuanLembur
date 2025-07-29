@extends('Admin.main')
@section('content_admin')
  
<main class="h-full overflow-y-auto">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid" x-data="{ isModalOpen: false }">

  {{-- Show data --}}
    <h2 class="my-6 text-2xl font-semibold text-gray-700">{{ $title }}</h2>
    <div class="card-body">
      <div class="mb-2">
        <button @click="isModalOpen = true" class=" btnAdd inline-block px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Tambah Data <i class="fa-solid fa-user-plus"></i></button>
      </div>
      <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="overflow-x-auto">
          <table id="positionTable" class="table w-full whitespace-no-wrap">
            <thead>
            <tr>
                {{-- <th class="px-4 py-3">No</th> --}}
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y">
              @foreach ($positions as $i => $item)
              <tr class="text-gray-700">
              {{-- <td>{{ $i + 1 }}</td> --}}
              <td class="px-4 py-3 text-sm">{{ $item->position }}</td>
              <td>
                <div class="flex items-center space-x-3">
                  <button type="button"
                  @click="isModalOpen = true"
                      class="text-purple-600 hover:text-purple-800 focus:outline-none editBtn" data-id="{{ $item->id }}" data-position="{{ $item->position }}">
                      <i class="fa-solid fa-pen"></i>
                  </button>
                  <button type="button"
                      class="text-purple-600 hover:text-purple-800 focus:outline-none deleteBtn" data-id="{{ $item->id }}">
                      <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>  
        </div>                  
      </div>                  
  </div>

  
{{-- Modal add dan edit --}}
<div
  x-cloak
  x-show="isModalOpen"
  x-transition:enter="transition ease-out duration-150"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>

<div
  x-show="isModalOpen"
  x-transition:enter="transition ease-out duration-150"
  x-transition:enter-start="opacity-0 transform translate-y-1/2"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0 transform translate-y-1/2"
  @click.away="isModalOpen = false"
  @keydown.escape.window="isModalOpen = false"
  class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
  role="dialog"
  aria-modal="true"
  aria-labelledby="modalTitle"
>

<header class="flex justify-between items-center mb-4">
  <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Tambah Jabatan</h2>
  <button type="button" @click="isModalOpen = false" class="text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>
</header>

    <!-- Form -->
  <form id="formJabatan" class="space-y-4">
    @csrf
      <input type="hidden" id="jabatanId" name="jabatan_id">
      <label for="position" class="block text-sm">
        <span class="text-gray-700">Nama Jabatan</span>
        <input
          name="position"
          value="{{ old('position') }}"
          autocomplete="off"
          class="block w-full mt-1 text-sm form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple @error('position') border-red-500 @enderror"
        />
      </label>

    <footer class="flex justify-end space-x-3">
      <button
        type="button"
        @click="isModalOpen = false"
        class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
        Cancel
      </button>
      <button
        type="submit"
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



$(document).ready(function() {

function resetFormJabatan() {
  $('.text-red-600').remove();
  $('.border-red-500').removeClass('border-red-500');
  $('#formJabatan')[0].reset();
  $('#jabatanId').val('');
  $('#formJabatan').removeData('url').removeData('method');
}

$('[x-data]').on('click', '[type="button"]', function () {
  resetFormJabatan();
});

// show data
$('#positionTable').DataTable();

// show Form add data
$(document).on('click', '.btnAdd', function () {

  $('#formJabatan')[0].reset();
  $('#modalTitle').text('Tambah Jabatan');

  const url = `{{ route("position.create") }}`;
  $('#formJabatan').data('url', url);
  $('#formJabatan').data('method', 'POST');

});

// show Form edit data
$(document).on('click', '.editBtn', function () {
  const id = $(this).data('id');
  const position = $(this).data('position');

  // title form
  $('#modalTitle').text('Edit Jabatan');

  // Isi field input
  $('[name="position"]').val(position);
  $('#jabatanId').val(id);

  // Ganti action AJAX ke update
  const url = `apps/admin/position/update/${id}`;
  $('#formJabatan').data('url', url);
  $('#formJabatan').data('method', 'PUT');
});


// Form add dan update data
$('#formJabatan').on('submit', function (e) {
  e.preventDefault();

  $('.text-red-600').remove();
  $('.border-red-500').removeClass('border-red-500');

  const id = $('#jabatanId').val();
  const isEdit = !!id;
  const url = isEdit ? `position/update/${id}` : '{{ route("position.create") }}';
  const method = isEdit ? 'PUT' : 'POST';

  $.ajax({
    url : url,
    type: "POST",
    dataType: "json",
    data: $(this).serialize() + (isEdit ? '&_method=PUT' : ''),
    success: function (response) {
      Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: isEdit ? 'Data berhasil diperbarui!' : 'Data berhasil disimpan!',
        }).then(() => {
          location.reload();
          // Tutup modal
          document.querySelector('[x-data]').__x.$data.isModalOpen = false;
          resetFormJabatan();
    });
  },
    error: function (xhr) {
   if (xhr.status === 422) {
    let errors = xhr.responseJSON.errors;
    // Loop semua error dan tampilkan di form
    $.each(errors, function(key, messages) {
        let input = $('[name="' + key + '"]');
        
        // Tambahkan border merah
        input.addClass('border-red-500');
        
        // Tambahkan pesan error setelah input
        input.after('<p class="mt-1 text-xs text-red-600">' + messages[0] + '</p>');
    });

    // Fokus ke input pertama yang error
    let firstErrorField = Object.keys(errors)[0];
    $('[name="' + firstErrorField + '"]').focus();

    } else {
        // Error lain selain 422, misalnya server error
        let msg = xhr.responseJSON?.error ?? 'Terjadi kesalahan tak terduga. Silakan coba lagi.';
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: msg
        });
      }
    }
  });
});

  $(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Konfirmasi Penghapusan',
        text: 'Apakah kamu yakin ingin menghapus data jabatan ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-trash-can"></i> Ya, Hapus!',
        cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
          popup: 'rounded-lg shadow-md',
          title: 'text-lg font-semibold text-gray-800',
          htmlContainer: 'text-sm text-gray-600',
          confirmButton: 'px-4 py-2 text-sm font-medium rounded bg-red-600 hover:bg-red-700 text-white',
          cancelButton: 'px-4 py-2 text-sm font-medium rounded bg-gray-200 hover:bg-gray-300 text-gray-800'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `position/delete/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.error ?? 'Terjadi kesalahan saat menghapus data.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg
                    });
                }
            });
        }
    });
});

});






</script>

@endsection



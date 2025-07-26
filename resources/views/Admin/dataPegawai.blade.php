@extends('Admin.main')
@section('content_admin')
  
<main class="h-full overflow-y-auto">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">
    @switch(($act))

        @case('add')

          <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Add Data</h2>
          <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('pegawai.create') }}" method="POST" class="space-y-4" id="createPegawaiForm">
              @csrf         
              <label for="" class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Nama</span>
                <input
                  name="name"
                  value="{{ old('name') }}"
                  class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('name') border-red-500 @enderror"
                />
              </label>
            
              <label for="" class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">NIP</span>
                <input
                  name="nip"
                  value="{{ old('nip') }}"
                  class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('nip') border-red-500 @enderror"
                />
              </label>
            
              <label for="" class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">No Telp</span>
                <input
                  name="no_tlp"
                  value="{{ old('no_tlp') }}"
                  class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('no_tlp') border-red-500 @enderror"
                />
              </label>
            
              <label for="" class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Email</span>
                <input
                  name="email"
                  type="email"
                  value="{{ old('email') }}"
                  class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('email') border-red-500 @enderror"
                  placeholder="email@example.com"
                />
              </label>
            
              <label for="" class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Password</span>
                <input
                  name="password"
                  type="password"
                  class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('password') border-red-500 @enderror"
                  placeholder="******"
                />
              </label>
            
              <label for="" class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Role</span>
                <select
                  name="role_id"
                  class="block w-full mt-1 text-sm form-select dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('role_id') border-red-500 @enderror"
                >
                <option value="">--- select ---</option>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                  {{ $role->role }}
                </option>
                @endforeach
              </select>
              </label>
            
              <label for="" class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Department</span>
                <select
                  name="department_id"
                  class="block w-full mt-1 text-sm form-select dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('department_id') border-red-500 @enderror"
                >
                <option value="">--- select ---</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                  {{ $department->department }}
                </option>
                @endforeach
                </select>
              </label>
            
              <label for="" class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Position</span>
                <select
                  name="position_id"
                  class="block w-full mt-1 text-sm form-select dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('position_id') border-red-500 @enderror"
                >
                <option value="">--- select ---</option>
                @foreach ($positions as $position)
                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                  {{ $position->position }}
                </option>
                @endforeach
                </select>
              </label>
            
              <div class="flex justify-end space-x-4 mt-6">
                <a
                  href="{{ route('pegawai.index') }}"
                  class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-300 border border-transparent rounded-md hover:bg-gray-400 focus:outline-none focus:shadow-outline-gray dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                >
                  Cancel
                </a>
                <button
                  type="submit"
                  class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                >
                  Save
                </button>
              </div>
            </form>
          </div>

        @break

        @case('edit')

            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Edit Data</h2>
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('pegawai.update', $editId) }}" method="POST" class="space-y-4" id="editPegawaiForm">
                @csrf
                @method('PUT')
              
            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">Nama</span>
              <input
                name="name_edit"
                value="{{ old('name', $editData->name ?? '') }}"
                class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('name') border-red-500 @enderror"
              />
            </label>

            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">NIP</span>
              <input
                name="nip_edit"
                value="{{ old('nip', $editData->nip ?? '') }}"
                class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('nip') border-red-500 @enderror"
              />
            </label>

            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">No Telp</span>
              <input
                name="no_tlp_edit"
                value="{{ old('no_tlp', $editData->no_tlp ?? '') }}"
                class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('no_tlp') border-red-500 @enderror"
              />
            </label>

            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">Email</span>
              <input
                name="email_edit"
                type="email"
                value="{{ old('email', $editData->email ?? '') }}"
                class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('email') border-red-500 @enderror"
                placeholder="email@example.com"
              />
            </label>

            <label class="block text-sm">
              <span class="text-gray-700 dark:text-gray-400">Password</span>
              <input
                name="password_edit"
                type="password"
                class="block w-full mt-1 text-sm form-input dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray @error('password') border-red-500 @enderror"
                placeholder="******"
              />
            </label>

            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Role</span>
              <select
                name="role_id_edit"
                class="block w-full mt-1 text-sm form-select dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('role_id') border-red-500 @enderror"
              >
                <option value="">--- select ---</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id', $editData->role_id ?? '') == $role->id ? 'selected' : '' }}>
                    {{ $role->role }}
                  </option>
                @endforeach
              </select>
            </label>

            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Department</span>
              <select
                name="department_id_edit"
                class="block w-full mt-1 text-sm form-select dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('department_id') border-red-500 @enderror"
              >
                <option value="">--- select ---</option>
                @foreach ($departments as $department)
                  <option value="{{ $department->id }}" {{ old('department_id', $editData->department_id ?? '') == $department->id ? 'selected' : '' }}>
                    {{ $department->department }}
                  </option>
                @endforeach
              </select>
            </label>

            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Position</span>
              <select
                name="position_id_edit"
                class="block w-full mt-1 text-sm form-select dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray @error('position_id') border-red-500 @enderror"
              >
                <option value="">--- select ---</option>
                @foreach ($positions as $position)
                  <option value="{{ $position->id }}" {{ old('position_id', $editData->position_id ?? '') == $position->id ? 'selected' : '' }}>
                    {{ $position->position }}
                  </option>
                @endforeach
              </select>
            </label>

            <div class="flex justify-end space-x-4 mt-6">
              <a
                href="{{ route('pegawai.index') }}"
                class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-gray-300 border border-transparent rounded-md hover:bg-gray-400 focus:outline-none focus:shadow-outline-gray dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
              >
                Cancel
              </a>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50"
              >
                Update
              </button>
            </div>
          </form>

          </div>
      
        @break

        @default

          <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $title }}</h2>
          <div class="mb-2">
            <a href="{{ route('pegawai.index', ['view' => 'add']) }}"
              class="inline-block px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <i class="fa-solid fa-user-plus mr-2"></i>Tambah Data
            </a>
          </div>
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                  <table id="pegawaiTable" class="w-full whitespace-no-wrap">
                  <thead>
                  <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                      {{-- <th class="text-gray-700 dark:text-gray-200">No</th> --}}
                      <th class="px-4 py-3">Nama</th>
                      <th class="px-4 py-3">Nip</th>
                      {{-- <th class="px-4 py-3">No Telp</th> --}}
                      <th class="px-4 py-3">Email</th>
                      <th class="px-4 py-3">Role</th>
                      <th class="px-4 py-3">Bidang</th>
                      <th class="px-4 py-3">Posisi</th>
                      <th class="px-4 py-3">Aksi</th>
                  </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              </tbody>
          </table>
        </div>            
      </div>      
    @endswitch
  </div>
</main>


<script>

$(document).ready(function() {

  $(document).on('click', '.deleteData', function() {
    let userId = $(this).data('id');

    Swal.fire({
        title: 'Anda yakin?',
        text: "Data akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/apps/admin/pegawai/delete/' + userId,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('.dataTable').DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                  console.log("Error response:", xhr.responseJSON);
                    let errorMessage = 'Terjadi kesalahan. Data tidak dapat dihapus.'; 
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage,
                    });
                }
            });
        }
    });
});



  // Tampil data 
      $('#pegawaiTable').DataTable({
          responsive: true,
          processing: true,
          serverSide: true,
          ajax: '{{ route("pegawai.data") }}',
          columns: [
              // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
              { data: 'name', name: 'name' },
              { data: 'nip', name: 'nip' },
              // { data: 'no_tlp', name: 'no_tlp' },
              { data: 'email', name: 'email' },
              { data: 'role', name: 'roles.role' },
              { data: 'department', name: 'departments.department' },
              { data: 'position', name: 'positions.position' },
              { data: 'action', name: 'action', orderable: false, searchable: false }
          ],  
           createdRow: function(row, data, dataIndex) {
              // Tambahkan class ke seluruh <td>
              $(row).find('td').addClass('px-4 py-3 text-sm');

              // Tambahkan class ke <tr>
              $(row).addClass('text-gray-700 dark:text-gray-400');
          }
      });
  });


  // Add
  $('#createPegawaiForm').on('submit', function(e) {
      e.preventDefault();

      // Bersihkan error lama
      $('.text-red-600').remove(); // Hapus pesan error lama
      $('.border-red-500').removeClass('border-red-500'); // Hapus border merah lama

      $.ajax({
          url: '{{ route("pegawai.create") }}',
          method: 'POST',
          data: $(this).serialize(),
          success: function(response) {
              Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: 'Data pegawai berhasil disimpan!',
              }).then(() => {
                  window.location.href = '{{ route("pegawai.index") }}';
              });
          },
          error: function(xhr) {
              if (xhr.status === 422) {
                  let errors = xhr.responseJSON.errors;

                  // Loop semua error dan tampilkan di form
                  $.each(errors, function(key, messages) {
                      let input = $('[name="' + key + '"]');
                      
                      // Tambahkan border merah
                      input.addClass('border-red-500');
                      
                      // Tambahkan pesan error setelah input
                      input.after('<p class="mt-1 text-xs text-red-600 dark:text-red-400">' + messages[0] + '</p>');
                  });

                  // Fokus ke input pertama yang error
                  let firstErrorField = Object.keys(errors)[0];
                  $('[name="' + firstErrorField + '"]').focus();
              } else {
                  // Error lain selain 422, misalnya server error
                  Swal.fire({
                      icon: 'error',
                      title: 'Gagal',
                      text: 'Terjadi kesalahan tak terduga. Silakan coba lagi.',
                  });
              }
          }
      });
  });

  // Form -> edit
  $(document).on('click', '.editData', function() {
        let userId = $(this).data('id');
        window.location.href = '{{ route("pegawai.edit") }}?id=' + userId;
  });

  // Update
  $('#editPegawaiForm').on('submit', function(e) {
      e.preventDefault();

      $('.text-red-600').remove();
      $('.border-red-500').removeClass('border-red-500');

      $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: $(this).serialize() + '&_method=PUT',
          success: function(response) {
              Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: response.message,
              }).then(() => {
                  window.location.href = '{{ route("pegawai.index") }}';
              });
          },
          error: function(xhr) {
              if (xhr.status === 422) {
                  let errors = xhr.responseJSON.errors;

                  $.each(errors, function(key, messages) {
                      // Mapping ke input dengan suffix _edit
                      let inputName = key + '_edit';
                      let input = $('[name="' + inputName + '"]');

                      input.addClass('border-red-500');
                      input.after('<p class="mt-1 text-xs text-red-600 dark:text-red-400">' + messages[0] + '</p>');
                  });

                  // Fokus ke input pertama yang error
                  let firstErrorField = Object.keys(errors)[0] + '_edit';
                  $('[name="' + firstErrorField + '"]').focus();
              } else {
                  Swal.fire({
                      icon: 'error',
                      title: 'Gagal',
                      text: 'Terjadi kesalahan tak terduga. Silakan coba lagi.',
                  });
              }
          }
  });


});



</script>

@endsection



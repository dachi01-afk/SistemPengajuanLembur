 @extends('Atasan.main')
 @section('content_atasan')
     
 <main class="h-full overflow-y-auto">
          <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700"
            >
              Daftar Data Pending
            </h2>
            
            <!-- New Table -->
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
              <div class="w-full overflow-x-auto">
                <table id="dataTable" class="w-full whitespace-no-wrap">
                  <thead>
                    <tr
                      class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50"
                    >
                      <th class="px-4 py-3">Nama</th>
                      <th class="px-4 py-3">Departemen</th>
                      <th class="px-4 py-3">Tanggal Pengajuan</th>
                      <th class="px-4 py-3">Jam </th>
                      <th class="px-4 py-3">Status</th>
                      <th class="px-4 py-3">Detail</th>
                      <th class="px-4 py-3">Aksi</th>
                    </tr>
                  </thead>
                  <tbody
                    class="bg-white divide-y"
                  >
                  @foreach ($requests as $request)
                    <tr class="text-gray-700">
                      <td class="px-4 py-3 text-sm">{{ $request->user->name ?? '-' }}</td>
                      <td class="px-4 py-3 text-sm">{{ $request->department->department ?? '-' }}</td>
                      <td class="px-4 py-3 text-sm">{{ $request->overtime_date }}</td>
                      <td class="px-4 py-3 text-sm">{{ $request->start_time }} - {{ $request->end_time }}</td>
                      <td class="px-4 py-3 text-xs">
                      @if ($request->status == 'pending') 
                        <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full">Pending</span>
                        @elseif($request->status =='approved')
                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Approved</span>
                        @elseif($request->status =='rejected')
                        <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Rejected</span>
                      @endif
                      </td>
                      <td class="px-4 py-3 text-sm">
                        <button @click="isModalOpen = true" data-id="{{ $request->id }}"class="btn-detail text-blue-600 hover:text-blue-900" title="Detail Pengajuan">
                          <i class="fas fa-eye"></i>
                        </button>
                      </td>
                      <td>
                        <div class="flex items-center space-x-3">
                          <button onclick="approveRequest({{ $request->id }})" class="approve-btn text-purple-600 hover:text-purple-800 focus:outline-none">
                            <i class="fas fa-check-circle text-xl"></i>
                          </button>
                          <button onclick="rejectRequest({{ $request->id }})" class="reject-btn text-purple-600 hover:text-purple-800 focus:outline-none">
                            <i class="fas fa-times-circle text-xl"></i>
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

<!-- Modal -->
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
  <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Detail Data</h2>
  <button type="button" @click="isModalOpen = false" class="text-gray-500 hover:text-gray-800 text-xl font-bold">&times;</button>
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
          <span id="detail-status" class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-gray-300 text-gray-800">-</span>
        </td>
      </tr>
    </tbody>
  </table>
</div>

</main>

<script>

function setStatusBadge(status) {
        const statusSpan = document.getElementById('detail-status');
        statusSpan.className = "inline-block px-2 py-1 rounded-full text-xs font-semibold";

        switch (status.toLowerCase()) {
          case 'approved':
            statusSpan.classList.add('px-2', 'py-1', 'font-semibold', 'leading-tight', 'text-green-700', 'bg-green-100', 'rounded-full', 'dark:bg-green-700', 'dark:text-green-100');
            break;
          case 'rejected':
            statusSpan.classList.add('px-2', 'py-1', 'font-semibold', 'leading-tight', 'text-red-700', 'bg-red-100', 'rounded-full', 'dark:text-red-100', 'dark:bg-red-700');
            break;
          default:
            statusSpan.classList.add('px-2', 'py-1', 'font-semibold', 'leading-tight', 'text-orange-700', 'bg-orange-100', 'rounded-full', 'dark:text-white', 'dark:bg-orange-600');
        }

        statusSpan.textContent = status.toUpperCase();
      }

  $(document).ready(function() {
    // $('#dataTable').DataTable();
  });

      // Approve
      function approveRequest(id) {
  Swal.fire({
    title: 'Setujui Pengajuan?',
    text: "Data ini akan disetujui!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Setujui!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`{{ url('apps/admin/approval/approve') }}/${id}`, {
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
        if(data.success){
          Swal.fire('Disetujui!', data.message, 'success')
            .then(() => location.reload());
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
        if (!value) {
          return 'Catatan harus diisi!';
        }
      }
    }).then((result) => {
      if (result.isConfirmed) {
        fetch(`{{ url('apps/admin/approval/reject') }}/${id}`, {
          method: 'PATCH',
          headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
          body: JSON.stringify({
            approval_note: result.value
          })
        })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            Swal.fire('Ditolak!', data.message, 'success')
              .then(() => location.reload());
          } else {
            Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
          }
        });
      }
    });
  }

  // detail data
  $(document).on('click', '.btn-detail', function () {
    const id = $(this).data('id');

    $.get('{{ route("history.detail", ":id") }}'.replace(':id', id), function (res) {
        const data = res.data;
        $('#detail-nama').text(data.nama);
        $('#detail-departemen').text(data.departemen);
        $('#detail-tanggal').text(data.tanggal_pengajuan);
        $('#detail-jam').text(data.jam);
        $('#detail-alasan').text(data.alasan);
        setStatusBadge(data.status);
        });
      });
</script>
@endsection
@extends('Pegawai.main')

@section('content_pegawai')
<main class="h-full overflow-y-auto">
  <style>[x-cloak]{display:none!important}</style>

  <div
    class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid"
    x-data="{ isDetailModalOpen: false, isFeedbackModalOpen: false }"
  >
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Data Penugasan Lembur</h2>

    <!-- Tabel -->
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
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y">
            @foreach ($dataPenugasan as $dpl)
              <tr class="text-gray-700">
                <td class="px-4 py-3 text-sm">{{ $dpl->user->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $dpl->department->department ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">{{ $dpl->overtime_date }}</td>
                <td class="px-4 py-3 text-sm">
                  {{ $dpl->start_time }} - {{ $dpl->end_time }}
                </td>
                <td class="px-4 py-3 text-xs">
                  @if ($dpl->status == 'pending')
                    <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full">Pending</span>
                  @elseif ($dpl->status == 'approved')
                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Approved</span>
                  @elseif ($dpl->status == 'rejected')
                    <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Rejected</span>
                  @else
                    <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">{{ ucfirst($dpl->status ?? '-') }}</span>
                  @endif
                </td>

                <!-- Detail -->
                <td class="px-4 py-3 text-sm text-center">
                  <button
                    @click="isDetailModalOpen = true"
                    data-id="{{ $dpl->id }}"
                    class="btn-detail inline-flex items-center justify-center rounded-md p-2 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300 text-blue-600 hover:text-blue-800 transition"
                    title="Detail Pengajuan" aria-label="Detail Pengajuan"
                  >
                    <i class="fas fa-eye text-lg"></i>
                  </button>
                </td>

                <!-- Aksi (Feedback) -->
                <td class="px-4 py-3 text-sm text-center">
                  @if ($dpl->feedback_submitted)
                    <button
                      type="button"
                      onclick="feedbackAlreadySent()"
                      class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 cursor-not-allowed"
                      title="Feedback sudah dikirim" aria-label="Feedback sudah dikirim" disabled
                    >
                      <i class="fa-solid fa-check text-lg"></i>
                    </button>
                  @else
                    <button
                      @click="isFeedbackModalOpen = true"
                      data-id="{{ $dpl->id }}"
                      class="btnFeedback inline-flex items-center justify-center rounded-md p-2 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-300 text-purple-600 hover:text-purple-800 transition"
                      title="Kirim Konfirmasi" aria-label="Kirim Konfirmasi"
                    >
                      <i class="fa-solid fa-paper-plane text-lg"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Detail -->
    <div
      x-cloak
      x-show="isDetailModalOpen"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-30 flex items-end bg-black/50 sm:items-center sm:justify-center"
    >
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
        role="dialog" aria-modal="true" aria-labelledby="modalTitleDetail"
      >
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
              <tr class="text-xs font-semibold tracking-wide text-left text-gray-600">
                <th class="py-3 px-4 text-left font-medium uppercase">File SPT</th>
                <td class="py-3 px-4">
                  <button
                    id="btn-preview-spt"
                    class="inline-flex items-center gap-2 px-3 py-1 text-sm text-white bg-purple-600 rounded hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled title="Preview SPT"
                  >
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

    <!-- Modal Feedback -->
    <div
      x-cloak
      x-show="isFeedbackModalOpen"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-30 flex items-end bg-black/50 sm:items-center sm:justify-center"
    >
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
        role="dialog" aria-modal="true" aria-labelledby="modalTitleFeedback"
      >
        <header class="flex justify-between items-center mb-4">
          <h2 id="modalTitleFeedback" class="text-lg font-semibold text-gray-700">Konfirmasi Selesai</h2>
          <button type="button" @click="isFeedbackModalOpen = false"
            class="text-gray-500 hover:text-gray-800 text-xl font-bold" aria-label="Tutup">&times;</button>
        </header>

        <form id="formFeedback" class="space-y-4">
          @csrf
          <input type="hidden" name="overtime_request_id" id="feedback_overtime_id">

          <div>
            <label for="activity_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Aktivitas</label>
            <textarea
              name="activity_description" id="activity_description" rows="4" required
              class="w-full border rounded p-2"
              placeholder="Tuliskan aktivitas yang dilakukan..."
            ></textarea>
          </div>

          <div>
            <label for="documentation" class="block text-sm font-medium text-gray-700 mb-1">Dokumentasi</label>
            <input
              type="file" name="documentation" id="documentation"
              class="w-full border rounded p-2" accept="image/*,application/pdf"
            >
          </div>

          <footer class="flex justify-end gap-3">
            <button type="button"
              @click="isFeedbackModalOpen = false"
              class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
              Cancel
            </button>
            <button id="btnSubmitFeedback" type="submit"
              class="px-4 py-2 text-sm text-white bg-purple-600 rounded-md hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed">
              Simpan
            </button>
          </footer>
        </form>
      </div>
    </div>
  </div>
</main>

{{-- Script --}}
<script>
  function feedbackAlreadySent() {
    Swal.fire({
      icon: 'info',
      title: 'Sudah Dikonfirmasi',
      text: 'Anda sudah mengirim feedback. Tidak bisa mengirim ulang.',
      confirmButtonColor: '#6b7280'
    });
  }

  function setStatusBadge(status) {
    const statusSpan = document.getElementById('detail-status');
    statusSpan.className = "inline-block px-2 py-1 rounded-full text-xs font-semibold";

    const s = (status || '').toString().toLowerCase();
    if (s === 'approved') {
      statusSpan.classList.add('text-green-700','bg-green-100','dark:bg-green-700','dark:text-green-100');
    } else if (s === 'rejected') {
      statusSpan.classList.add('text-red-700','bg-red-100','dark:text-red-100','dark:bg-red-700');
    } else {
      statusSpan.classList.add('text-orange-700','bg-orange-100','dark:text-white','dark:bg-orange-600');
    }
    statusSpan.textContent = (status || '-').toString().toUpperCase();
  }

  // Detail data
  $(document).on('click', '.btn-detail', function() {
    const id = $(this).data('id');

    $.get('{{ route('penugasan.detail.pegawai', ':id') }}'.replace(':id', id), function(res) {
      const data = res?.data || {};
      $('#detail-nama').text(data.nama ?? '-');
      $('#detail-departemen').text(data.departemen ?? '-');
      $('#detail-tanggal').text(data.tanggal_pengajuan ?? '-');
      $('#detail-jam').text(data.jam ?? '-');
      $('#detail-alasan').text(data.alasan ?? '-');
      setStatusBadge(data.status);

      const previewBtn = $('#btn-preview-spt');
      if (data.spt_url) {
        previewBtn.data('url', data.spt_url).prop('disabled', false);
      } else {
        previewBtn.data('url', '').prop('disabled', true);
      }
    }).fail(() => {
      // fallback: reset isi
      $('#detail-nama, #detail-departemen, #detail-tanggal, #detail-jam, #detail-alasan').text('-');
      setStatusBadge('-');
      $('#btn-preview-spt').data('url','').prop('disabled', true);
    });
  });

  // Preview SPT
  $(document).on('click', '#btn-preview-spt', function() {
    const url = $(this).data('url');
    if (url) window.open(url, '_blank');
  });

  // Buka modal feedback & set ID
  $(document).on('click', '.btnFeedback', function() {
    const requestId = $(this).data('id');
    $('#feedback_overtime_id').val(requestId);
  });

  // Submit feedback
  $('#formFeedback').on('submit', function(e) {
    e.preventDefault();

    const $btn = $('#btnSubmitFeedback');
    $btn.prop('disabled', true);

    const formData = new FormData(this);

    $.ajax({
      url: '{{ route('penugasan.feedback') }}',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: formData,
      contentType: false,
      processData: false
    })
    .done(function(response) {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: response.message,
      }).then(() => {
        location.reload();
      });
    })
    .fail(function(xhr) {
      let errorMsg = 'Terjadi kesalahan';
      if (xhr.responseJSON?.errors) {
        errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
      }
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: errorMsg,
      });
    })
    .always(function() {
      $btn.prop('disabled', false);
    });
  });
</script>
@endsection

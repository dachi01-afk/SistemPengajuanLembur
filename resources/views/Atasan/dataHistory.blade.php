@extends('Atasan.main')

@section('content_atasan')
<main class="h-full overflow-y-auto" x-data="{ isModalOpen: false }">
  <style>[x-cloak]{display:none!important}</style>

  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Riwayat Pengajuan Lembur</h2>

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
            </tr>
          </thead>

          <tbody class="bg-white divide-y">
            @forelse ($groupedPengajuan as $sptNumber => $items)
              {{-- Header Grup SPT --}}
              <tr class="bg-indigo-50/60 border-t">
                <td class="px-4 py-2 text-sm font-semibold text-indigo-900" colspan="4">
                  Nomor SPT: {{ $sptNumber ?: '-' }}
                  <span class="ml-2 text-gray-500 font-normal">({{ $items->count() }} pegawai)</span>
                </td>
                <td class="px-4 py-2 text-sm text-right" colspan="2">
                  {{-- (Opsional) tambahkan tombol export / filter per grup di sini --}}
                </td>
              </tr>

              {{-- Item per pegawai dalam grup --}}
              @foreach ($items as $pengajuan)
                <tr class="text-gray-700">
                  <td class="px-4 py-3 text-sm">{{ $pengajuan->user->name ?? '-' }}</td>
                  <td class="px-4 py-3 text-sm">{{ $pengajuan->department->department ?? '-' }}</td>
                  <td class="px-4 py-3 text-sm">{{ $pengajuan->overtime_date }}</td>
                  <td class="px-4 py-3 text-sm">{{ $pengajuan->start_time }} - {{ $pengajuan->end_time }}</td>
                  <td class="px-4 py-3 text-xs">
                    @if ($pengajuan->status == 'approved')
                      <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Approved</span>
                    @elseif ($pengajuan->status == 'rejected')
                      <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Rejected</span>
                    @else
                      <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">
                        {{ ucfirst($pengajuan->status ?? '-') }}
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-sm text-center">
                    <button
                      @click="isModalOpen = true"
                      data-id="{{ $pengajuan->id }}"
                      class="btn-detail inline-flex items-center justify-center rounded-md p-2 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300 text-blue-600 hover:text-blue-800 transition"
                      title="Detail Pengajuan" aria-label="Detail Pengajuan">
                      <i class="fas fa-eye text-lg"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            @empty
              <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada riwayat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Detail -->
  <div
    x-cloak
    x-show="isModalOpen"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 flex items-end bg-black/50 sm:items-center sm:justify-center">

    <div
      x-show="isModalOpen"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 translate-y-1/2"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 translate-y-1/2"
      @click.away="isModalOpen = false"
      @keydown.escape.window="isModalOpen = false"
      class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
      role="dialog" aria-modal="true" aria-labelledby="modalTitle">

      <header class="flex justify-between items-center mb-4">
        <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Detail Data</h2>
        <button type="button" @click="isModalOpen = false"
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
              <th class="py-3 px-4 text-left font-medium uppercase">Catatan</th>
              <td class="py-3 px-4" id="detail-catatan">-</td>
            </tr>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 border-b bg-gray-50">
              <th class="py-3 px-4 text-left font-medium uppercase">Diproses Oleh</th>
              <td class="py-3 px-4" id="detail-diproses-oleh">-</td>
            </tr>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-600">
              <th class="py-3 px-4 text-left font-medium uppercase">Tanggal Proses</th>
              <td class="py-3 px-4" id="detail-tanggal-proses">-</td>
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
    const span = document.getElementById('detail-status');
    span.className = "inline-block px-2 py-1 rounded-full text-xs font-semibold";

    const s = (status || '').toString().toLowerCase();
    if (s === 'approved') {
      span.classList.add('text-green-700', 'bg-green-100', 'dark:bg-green-700', 'dark:text-green-100');
    } else if (s === 'rejected') {
      span.classList.add('text-red-700', 'bg-red-100', 'dark:bg-red-700', 'dark:text-red-100');
    } else {
      span.classList.add('text-gray-800', 'bg-gray-200', 'dark:bg-gray-600', 'dark:text-white');
    }
    span.textContent = (status || '-').toString().toUpperCase();
  }

  $(document).ready(function () {
    // Aktifkan DataTable kalau dipakai:
    // $('#dataTable').DataTable();

    // Handler detail
    $(document).on('click', '.btn-detail', function () {
      const id = $(this).data('id');
      $.get('{{ route('history.detail.atasan', ':id') }}'.replace(':id', id), function (res) {
        const d = res?.data || {};
        $('#detail-nama').text(d.nama ?? '-');
        $('#detail-departemen').text(d.departemen ?? '-');
        $('#detail-tanggal').text(d.tanggal_pengajuan ?? '-');
        $('#detail-jam').text(d.jam ?? '-');
        $('#detail-alasan').text(d.alasan ?? '-');
        $('#detail-catatan').text(d.catatan ?? '-');
        $('#detail-diproses-oleh').text(d.diproses_oleh ?? '-');
        $('#detail-tanggal-proses').text(d.tanggal_proses ?? '-');
        setStatusBadge(d.status);
      }).fail(() => {
        $('#detail-nama, #detail-departemen, #detail-tanggal, #detail-jam, #detail-alasan, #detail-catatan, #detail-diproses-oleh, #detail-tanggal-proses').text('-');
        setStatusBadge('-');
      });
    });
  });
</script>
@endsection

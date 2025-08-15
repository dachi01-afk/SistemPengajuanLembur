@extends('Atasan.main')
@section('content_atasan')
<main class="h-full overflow-y-auto">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Pengajuan Lembur Baru</h2>

    <form id="formLembur" action="{{ route('pengajuan.createbyatasan') }}" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- 2 Kolom: Daftar Pegawai (kiri) & Terpilih (kanan) --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kolom kiri: pilih pegawai --}}
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Pilih Pegawai <span class="text-gray-400">(double click untuk memilih)</span>
          </label>
          <div class="flex items-center gap-2 mb-2">
            <input type="text" id="filterPegawai" placeholder="Cari nama/NIP..."
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button type="button" id="btnClearSelection"
                    class="px-3 py-2 text-xs rounded-lg border border-gray-300 hover:bg-gray-100">Clear</button>
          </div>

          <ul id="listPegawai" class="border border-gray-300 rounded-lg h-[320px] overflow-y-auto bg-white text-sm">
            @foreach ($employees as $emp)
              <li data-id="{{ $emp->id }}"
                  class="px-3 py-2 border-b border-gray-200 hover:bg-purple-50 cursor-pointer select-none">
                {{ $emp->name }} ({{ $emp->nip }})
              </li>
            @endforeach
          </ul>
          <p id="selectedCount" class="text-xs text-gray-500 mt-2">0 pegawai dipilih</p>
        </div>

        {{-- Kolom kanan: pegawai yang dipilih --}}
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">
            Pegawai yang Dipilih <span class="text-gray-400">(double click untuk menghapus)</span>
          </label>
          <ul id="listTerpilih" class="border border-gray-300 rounded-lg h-[320px] overflow-y-auto bg-gray-50 text-sm">
            <li class="text-gray-400 italic px-3 py-2">Belum ada pegawai dipilih</li>
          </ul>
        </div>
      </div>

      {{-- Hidden input untuk kirim ID pegawai terpilih --}}
      <div id="pegawaiHidden"></div>

      {{-- Detail lembur --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Departemen</label>
          <input type="text" id="department_name"
                 class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                 readonly value="{{ $department->department }}">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lembur</label>
          <input type="date" name="tanggal"
                 class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                 required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Jam Mulai</label>
          <input type="time" name="jam_mulai"
                 class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                 required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Jam Selesai</label>
          <input type="time" name="jam_selesai"
                 class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                 required>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-600 mb-1">Keterangan</label>
          <textarea name="keterangan" rows="4"
                    class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required placeholder="Contoh: Menyelesaikan laporan bulanan..."></textarea>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-600 mb-1">Nomor SPT</label>
          <input type="text" name="spt_number"
                 class="w-full px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                 required placeholder="Contoh: 094/SPT/VIII/2025">
        </div>

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

      <div class="mt-6 text-right">
        <button type="submit"
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200 ease-in-out">
          Kirim Pengajuan
        </button>
      </div>
    </form>
  </div>
</main>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const listPegawai   = document.getElementById('listPegawai');
  const listTerpilih  = document.getElementById('listTerpilih');
  const hiddenInput   = document.getElementById('pegawaiHidden');
  const filterInput   = document.getElementById('filterPegawai');
  const selectedCount = document.getElementById('selectedCount');

  let pdfBlobUrl = '';
  const fileInput = document.querySelector('input[name="spt_file"]');
  const btnPreview = document.getElementById('btnPreviewPDF');
  const btnClear = document.getElementById('btnClearSelection');

  function updateHiddenInput() {
    hiddenInput.innerHTML = '';
    const ids = Array.from(listTerpilih.querySelectorAll('li[data-id]'))
      .map(li => li.getAttribute('data-id'));
    ids.forEach(id => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'pegawai_id[]';
      input.value = id;
      hiddenInput.appendChild(input);
    });
    selectedCount.textContent = ids.length + ' pegawai dipilih';
  }

  function ensureEmptyText() {
    if (listTerpilih.querySelectorAll('li[data-id]').length === 0) {
      listTerpilih.innerHTML = '<li class="text-gray-400 italic px-3 py-2">Belum ada pegawai dipilih</li>';
    }
  }

  function addToSelected(sourceLi) {
    const id = sourceLi.getAttribute('data-id');
    if (listTerpilih.querySelector(`li[data-id="${id}"]`)) return; // hindari duplikat

    // hapus empty text
    const empty = listTerpilih.querySelector('.italic');
    if (empty) empty.remove();

    const newLi = document.createElement('li');
    newLi.setAttribute('data-id', id);
    newLi.className = 'px-3 py-2 border-b border-gray-200 hover:bg-red-50 cursor-pointer select-none';
    newLi.textContent = sourceLi.textContent;

    listTerpilih.appendChild(newLi);
    updateHiddenInput();
  }

  function removeFromSelected(targetLi) {
    targetLi.remove();
    ensureEmptyText();
    updateHiddenInput();
  }

  // Double click kiri -> tambah
  listPegawai.addEventListener('dblclick', function (e) {
    if (e.target.tagName === 'LI' && e.target.hasAttribute('data-id')) {
      addToSelected(e.target);
    }
  });

  // Double click kanan -> hapus
  listTerpilih.addEventListener('dblclick', function (e) {
    if (e.target.tagName === 'LI' && e.target.hasAttribute('data-id')) {
      removeFromSelected(e.target);
    }
  });

  // Filter kiri
  filterInput.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    Array.from(listPegawai.querySelectorAll('li')).forEach(li => {
      const text = li.textContent.toLowerCase();
      li.style.display = text.includes(q) ? '' : 'none';
    });
  });

  // Clear (kosongkan pilihan kanan)
  btnClear.addEventListener('click', function () {
    listTerpilih.innerHTML = '';
    ensureEmptyText();
    updateHiddenInput();
  });

  // Preview PDF
  fileInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.type === "application/pdf") {
      pdfBlobUrl = URL.createObjectURL(file);
      btnPreview.classList.remove('hidden');
    } else {
      pdfBlobUrl = '';
      btnPreview.classList.add('hidden');
    }
  });

  btnPreview.addEventListener('click', function () {
    if (pdfBlobUrl) window.open(pdfBlobUrl, '_blank');
  });

  // Submit AJAX (jQuery + Swal)
  const form = document.getElementById('formLembur');
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    // Pastikan minimal 1 pegawai
    if (hiddenInput.querySelectorAll('input[name="pegawai_id[]"]').length === 0) {
      if (window.Swal) {
        Swal.fire({icon:'warning',title:'Perhatian',text:'Pilih minimal satu pegawai (double click di daftar kiri).'});
      } else {
        alert('Pilih minimal satu pegawai.');
      }
      return;
    }

    const formData = new FormData(form);

    $.ajax({
      url: '{{ route('pengajuan.createbyatasan') }}',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: response.message,
        }).then(() => {
          window.location.href = '{{ route('pengajuan.index.atasan') }}';
        });
      },
      error: function (xhr) {
        const msg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Terjadi kesalahan';
        Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
      }
    });
  });
});
</script>
@endsection

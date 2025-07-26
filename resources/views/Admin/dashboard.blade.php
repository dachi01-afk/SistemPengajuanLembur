 @extends('Admin.main')
 @section('content_admin')
     
 <main class="h-full overflow-y-auto">
          <div class=" px-6 mx-auto grid ">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Dashboard
            </h2>
            <!-- Cards -->
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">


         <!-- Total Pengajuan -->
          <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-blue-100 text-blue-600 rounded-full dark:bg-blue-500 dark:text-white text-xl">
              <i class="fa-solid fa-briefcase"></i>
            </div>
            <div>
              <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengajuan</p>
              <p class="text-xl font-bold text-gray-700 dark:text-gray-200">{{ $totalPengajuan }}</p>
            </div>
          </div>

          <!-- Total Pending -->
          <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-yellow-100 text-yellow-600 rounded-full dark:bg-yellow-500 dark:text-white text-xl">
              <i class="fa-solid fa-clock"></i>
            </div>
            <div>
              <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Pending</p>
              <p class="text-xl font-bold text-gray-700 dark:text-gray-200">{{ $totalPending }}</p>
            </div>
          </div>

          <!-- Total Disetujui -->
          <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-green-100 text-green-600 rounded-full dark:bg-green-500 dark:text-white text-xl">
              <i class="fa-solid fa-check"></i>
            </div>
            <div>
              <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Disetujui</p>
              <p class="text-xl font-bold text-gray-700 dark:text-gray-200">{{ $totalApproved }}</p>
            </div>
          </div>

          <!-- Total Ditolak -->
          <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-red-100 text-red-600 rounded-full dark:bg-red-500 dark:text-white text-xl">
              <i class="fa-solid fa-xmark"></i>
            </div>
            <div>
              <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Total Ditolak</p>
              <p class="text-xl font-bold text-gray-700 dark:text-gray-200">{{ $totalRejected }}</p>
            </div>
          </div>
   
            </div>

            <!-- New Table -->
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
              <div class="w-full overflow-x-auto">
                <table id="dataTable" class="w-full whitespace-no-wrap">
                  <thead>
                    <tr
                      class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                    >
                      <th class="px-4 py-3">Nama</th>
                      <th class="px-4 py-3">Departemen</th>
                      <th class="px-4 py-3">Tanggal Pengajuan</th>
                      <th class="px-4 py-3">Jam </th>
                      <th class="px-4 py-3">Alasan</th>
                      <th class="px-4 py-3">Status</th>
                    </tr>
                  </thead>
                  <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                  >
                  @foreach ($dataPengajuan as $pengajuan)
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3 text-sm">{{ $pengajuan->user->name ?? '-' }}</td>
                      <td class="px-4 py-3 text-sm">{{ $pengajuan->department->department ?? '-' }}</td>
                      <td class="px-4 py-3 text-sm">{{ $pengajuan->overtime_date }}</td>
                      <td class="px-4 py-3 text-sm">{{ $pengajuan->start_time }} - {{ $pengajuan->end_time }}</td>
                      <td class="px-4 py-3 text-sm">{{ $pengajuan->reason }}</td>
                      <td class="px-4 py-3 text-xs">
                        @if ($pengajuan->status == 'pending') 
                          <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">Pending</span>
                          @elseif($pengajuan->status =='approved')
                          <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Approved</span>
                          @elseif($pengajuan->status =='rejected')
                          <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">Rejected</span>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
</main>

<script>
  $(document).ready(function() {
    // $('#dataTable').DataTable();
  });
</script>



@endsection
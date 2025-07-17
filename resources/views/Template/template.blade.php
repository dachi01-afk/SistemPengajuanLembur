<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Windmill Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />

	{{-- fontawsome --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- alert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.output.css') }}" />
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="{{ asset('assets/js/init-alpine.js') }}"></script>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"
    />
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
      defer
    ></script>
    <script src="{{ asset('assets/js/charts-lines.js') }}" defer></script>
    <script src="{{ asset('assets/js/charts-pie.js') }}" defer></script>
	
	
	{{-- jquery --}}
	<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css"/>
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css"> --}}

	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
  {{-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> --}}


  <style>
    .table-wrapper {
      max-width: 1200px;
      margin: 0 auto;
      overflow-x: auto;
    }

    [x-cloak] { display: none !important; }
    </style>

  
  </head>  

  @yield('content')

</body>
</html>

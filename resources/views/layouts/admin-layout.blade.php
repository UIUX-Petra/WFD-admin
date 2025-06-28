<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <link rel="icon" href="{{ asset('image/p2p logo.svg') }}" type="image/svg+xml">
    
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <meta name="server-date" content="{{ now()->toDateString() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: #374151;
            color: #ffffff;
        }

        .sidebar-link.active {
            border-left: 3px solid #3b82f6;
        }

        .sidebar-link {
            padding-left: calc(1rem - 3px);
        }
    </style>
    @yield('style')
</head>

<body class="bg-gray-100 font-sans antialiased">
   <div x-data="{ sidebarOpen: true, sidebarExpanded: true }" class="flex h-screen bg-gray-200">
        @include('partials._sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('partials._header')

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="container mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Toastify JS --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @stack('scripts')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonColor: "#56843a",
            })
        </script>
    @endif
</body>

</html>

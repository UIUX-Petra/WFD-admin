<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: #374151;
            /* gray-700 */
            color: #ffffff;
        }

        .sidebar-link.active {
            border-left: 3px solid #3b82f6;
            /* blue-500 */
        }

        .sidebar-link {
            padding-left: calc(1rem - 3px);
            /* Adjust padding for active border */
        }

   
    </style>
    @yield('style')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen bg-gray-200">
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

    @stack('scripts')
</body>

</html>

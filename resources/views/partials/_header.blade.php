<header class="flex items-center justify-between px-6 py-4 bg-white shadow-sm border-b border-gray-200">
    <!-- Sidebar Toggle (Mobile) -->
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 md:hidden focus:outline-none hover:text-blue-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h11" />
        </svg>
    </button>

    <!-- Spacer to push content to the right -->
    <div class="flex-1"></div>

    <!-- User Menu -->
    <div x-data="{ dropdownOpen: false }" class="relative">
        <button @click="dropdownOpen = !dropdownOpen"
                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 transition focus:outline-none">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=random&color=fff"
                 alt="Avatar"
                 class="h-9 w-9 rounded-full object-cover shadow">
            <div class="hidden md:flex flex-col items-start text-sm leading-tight">
                <span class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin Name' }}</span>
                <span class="text-gray-500 text-xs">Admin</span>
            </div>
            <i class="ri-arrow-down-s-line hidden md:inline-block text-gray-600 text-lg"></i>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-lg shadow-md overflow-hidden ring-1 ring-gray-100"
             x-cloak>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil (Coming Soon)</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                Logout
            </a>
        </div>
    </div>
</header>

<header class="flex items-center justify-between p-4 bg-white border-b">
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div class="relative hidden md:block">
        {{-- <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search...">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i class="ri-search-line text-gray-400"></i>
        </span> --}}
    </div>

    <div x-data="{ dropdownOpen: false }" class="relative">
        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 relative focus:outline-none">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=random&color=fff" alt="Avatar" class="h-9 w-9 rounded-full object-cover">
            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin Name' }}</span>
            <i class="ri-arrow-down-s-line hidden md:block text-gray-700"></i>
        </button>

        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-20"
             x-cloak
        >
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">Profil (Soon)</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-500 hover:text-white">
                Logout
            </a>
        </div>
    </div>
</header>
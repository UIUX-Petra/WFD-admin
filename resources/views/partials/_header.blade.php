<header
    class="flex items-center justify-between px-6 py-4 bg-[#eaf3f8] shadow-sm border-b border-gray-300 text-gray-800">
    <!-- Sidebar Toggle (Mobile) -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="text-gray-600 md:hidden focus:outline-none hover:text-blue-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h11" />
        </svg>
    </button>

    <!-- Spacer to push content to the right -->
    <div class="flex-1"></div>

    <!-- User Menu -->
    <div x-data="{ dropdownOpen: false }" class="relative">
        <button @click="dropdownOpen = !dropdownOpen"
            class="flex items-center gap-3 px-3 py-2 rounded-md transition focus:outline-none hover:bg-[#d0e2f0]">

            <img src="https://ui-avatars.com/api/?name={{ urlencode(session('name') ?? 'Admin') }}&background=random"
                alt="Avatar" class="h-9 w-9 rounded-full object-cover shadow">

            <div class="hidden md:flex flex-col items-start text-sm leading-tight">
                <span class="font-semibold text-gray-800">{{ session('name') ?? 'Admin' }}</span>
                <span class="text-gray-500 text-xs">Admin</span>
            </div>
            <i class="ri-arrow-down-s-line hidden md:inline-block text-gray-600 text-lg"></i>

        </button>

        <!-- Dropdown Menu -->
        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute right-0 z-50 mt-2 w-48 bg-[#eaf3f8] rounded-lg shadow-md overflow-hidden ring-1 ring-gray-300"
            x-cloak>
            <a href="#" class="block px-4 py-2 text-sm text-gray-800 transition"
                style="background-color: transparent;"
                onmouseover="this.style.backgroundColor='#aac4e2'; this.style.color='#1f2937';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#374151';">
                Profil (Coming Soon)
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="w-full">
                @csrf
                <button
                    type="submit"class="w-full flex justify-start block px-4 py-2 text-sm text-red-500 hover:bg-red-100 hover:text-red-700 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>

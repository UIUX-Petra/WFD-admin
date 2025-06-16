<aside
    x-show="sidebarOpen"
    @click.away="if (window.innerWidth < 768) sidebarOpen = false"
    x-transition:enter="transition ease-in-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 text-gray-100 p-4 space-y-6 transform md:relative md:translate-x-0"
    x-cloak
>

    <a href="{{ route('admin.dashboard.main') }}" class="flex items-center space-x-2 px-2">
        <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
        </svg>
        <span class="text-2xl font-bold">Admin P2P</span>
    </a>

    <nav class="space-y-2">
        <a href="{{ route('admin.dashboard.main') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="ri-dashboard-line"></i>
            <span>Dashboard</span>
        </a>

        <div>
            <h3 class="px-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Users</h3>
            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.activity') ? 'active' : '' }}">
                <i class="ri-group-line"></i>
                <span>Users Data</span>
            </a>
        </div>

        <div>
            <h3 class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Moderation</h3>
            <a href="{{ route('admin.moderation.dashboard') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.moderation.dashboard') ? 'active' : '' }}">
                <i class="ri-shield-check-line"></i>
                <span>Moderation Dashboard</span>
            </a>
            {{-- <a href="{{ route('admin.moderation.questions') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.moderation.questions') ? 'active' : '' }}">
                <i class="ri-question-answer-line"></i>
                <span>Question Reports</span>
            </a> --}}
            <a href="{{ route('admin.moderation.reports') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.moderation.answers') ? 'active' : '' }}">
                <i class="ri-chat-quote-line"></i>
                <span>Content Reports</span>
            </a>
            {{-- {{ route('admin.moderation.answers') }} --}}
             {{-- <a href="" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.moderation.comments') ? 'active' : '' }}">
                <i class="ri-message-2-line"></i>
                <span>Comment Reports</span>
            </a> --}}
            {{-- {{ route('admin.moderation.comments') }} --}}
            {{-- <a href="{{ route('admin.content.manage') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.content.manage') ? 'active' : '' }}">
                 <i class="ri-pencil-ruler-2-line"></i>
                <span>Content Management</span>
            </a> --}}
            {{-- <a href="" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.moderation.log') ? 'active' : '' }}">
                <i class="ri-history-line"></i>
                <span>Moderation Log</span>
            </a> --}}
            {{-- {{ route('admin.moderation.log') }} --}}
        </div>

        <div>
            <h3 class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Academic</h3>
            <a href="{{ route('admin.subjects.index') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.subjects.index') ? 'active' : '' }}">
                <i class="ri-book-3-line"></i>
                <span>Subjects Management</span>
            </a>
        </div>

        {{-- <div>
            <h3 class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Support</h3>
            <a href="{{ route('admin.support.index') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.support.index') ? 'active' : '' }}">
                <i class="ri-customer-service-2-line"></i>
                <span>Help CS</span>
            </a>
        </div> --}}
        
        <div>
            <h3 class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Platform</h3>
             <a href="{{ route('admin.announcements.index') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.announcements.index') ? 'active' : '' }}">
                <i class="ri-megaphone-line"></i>
                <span>Announcement</span>
            </a>
           
            {{-- Link ke Manajemen Halaman Statis bisa ditambahkan di sini --}}
        </div>
         <div>
            <h3 class="px-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Web Roles</h3>
            <a href="{{ route('admin.platform.roles') }}" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.activity') ? 'active' : '' }}">
                <i class="ri-group-line"></i>
                <span>Roles</span>
            </a>
        </div>

        <div class="pt-4 mt-4 border-t border-gray-700">
             <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link hover:bg-red-700">
                <i class="ri-logout-box-r-line"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>
</aside>
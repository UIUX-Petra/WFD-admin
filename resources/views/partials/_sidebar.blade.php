<style>
    aside, aside * {
        font-family: sans-serif !important;
    }
</style>

<aside
    x-data="{ sidebarOpen: true, sidebarExpanded: true }"
    x-show="sidebarOpen"
    @click.away="if (window.innerWidth < 768) sidebarOpen = false"
    x-transition:enter="transition ease-in-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    :class="sidebarExpanded ? 'w-64' : 'w-20'"
    class="fixed inset-y-0 left-0 z-30 bg-[#2e304f] text-gray-100 p-4 transform md:relative md:translate-x-0 transition-all duration-300 ease-in-out overflow-hidden"
    x-cloak
>
    <nav class="flex flex-col space-y-2">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard.main') }}"
           class="flex items-center justify-center md:justify-start space-x-2 py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <span x-show="sidebarExpanded"
                  class="text-xl font-bold tracking-wide transition-opacity duration-200">Admin P2P</span>
        </a>

        <!-- Toggle Button -->
        <a href="#" @click.prevent="sidebarExpanded = !sidebarExpanded"
           class="flex items-center justify-center md:justify-start py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
            <i class="ri-menu-line text-xl"></i>
            <span x-show="sidebarExpanded" class="ml-2">Toggle Sidebar</span>
        </a>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard.main') }}"
           class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
           {{ request()->routeIs('admin.dashboard') 
                ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
            <i class="ri-dashboard-line text-xl"></i>
            <span x-show="sidebarExpanded">Dashboard</span>
        </a>

        <!-- Users -->
        <div>
            <h3 x-show="sidebarExpanded" class="px-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Users</h3>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
               {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.activity') 
                    ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                    : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
                <i class="ri-group-line text-xl"></i>
                <span x-show="sidebarExpanded">Users Data</span>
            </a>
        </div>

        <!-- Moderation -->
        <div>
            <h3 x-show="sidebarExpanded" class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Moderation</h3>
            <a href="{{ route('admin.moderation.dashboard') }}"
               class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
               {{ request()->routeIs('admin.moderation.dashboard') 
                    ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                    : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
                <i class="ri-shield-check-line text-xl"></i>
                <span x-show="sidebarExpanded">Moderation Dashboard</span>
            </a>

            <a href="{{ route('admin.moderation.reports') }}"
               class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
               {{ request()->routeIs('admin.moderation.reports') 
                    ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                    : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
                <i class="ri-chat-quote-line text-xl"></i>
                <span x-show="sidebarExpanded">Content Reports</span>
            </a>
        </div>

        <!-- Academic -->
        <div>
            <h3 x-show="sidebarExpanded" class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Academic</h3>
            <a href="{{ route('admin.subjects.index') }}"
               class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
               {{ request()->routeIs('admin.subjects.index') 
                    ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                    : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
                <i class="ri-book-3-line text-xl"></i>
                <span x-show="sidebarExpanded">Subjects Management</span>
            </a>
        </div>

        <!-- Platform -->
        <div>
            <h3 x-show="sidebarExpanded" class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Platform</h3>
            <a href="{{ route('admin.announcements.index') }}"
               class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
               {{ request()->routeIs('admin.announcements.index') 
                    ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                    : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
                <i class="ri-megaphone-line text-xl"></i>
                <span x-show="sidebarExpanded">Announcement</span>
            </a>
        </div>

        <!-- Web Roles -->
        <div>
            <h3 x-show="sidebarExpanded" class="px-4 mt-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Web Roles</h3>
            <a href="{{ route('admin.platform.roles') }}"
               class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link
               {{ request()->routeIs('admin.platform.roles') 
                    ? 'bg-gray-700 text-[#ddab41] font-semibold border-l-4 border-blue-500' 
                    : 'text-gray-300 hover:bg-gray-700 hover:text-[#ddab41] border-l-4 border-transparent' }}">
                <i class="ri-group-line text-xl"></i>
                <span x-show="sidebarExpanded">Roles</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-600"></div>

        <!-- Logout -->
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center space-x-2 py-2.5 px-4 rounded transition duration-200 sidebar-link hover:bg-red-700 hover:text-[#ddab41]">
            <i class="ri-logout-box-r-line text-xl"></i>
            <span x-show="sidebarExpanded">Logout</span>
        </a>
        <form id="logout-form" action="" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</aside>

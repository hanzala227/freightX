<div class="page-header navbar navbar-fixed-top bg-white border-b border-gray-200 h-[50px] flex items-center justify-between px-4 shadow-sm relative z-[1001] !overflow-visible">
    <!-- Left: Sidebar Toggler & Logo (Logic handled by layout/sidebar usually, but adding toggler here) -->
    <div class="flex items-center">
        <button onclick="if(window.innerWidth > 768) { document.body.classList.toggle('sidebar-collapsed'); window.dispatchEvent(new CustomEvent('sidebar-toggled')); } else { document.body.classList.toggle('sidebar-mobile-open'); }" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
            <i class="fa fa-bars text-lg"></i>
        </button>

        <!-- Branch Selection (Left Side) -->
        <div class="relative ml-2" x-data="{ open: false }">
            <div @click="open = !open" class="flex items-center space-x-1 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded cursor-pointer hover:bg-gray-100 transition-colors">
                <i class="fa fa-building-o text-gray-500 text-[10px]"></i>
                <span class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">Main Branch</span>
                <i class="fa fa-angle-down ml-1 text-gray-400 text-[10px]" :class="open ? 'rotate-180' : ''"></i>
            </div>
            
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition
                 class="absolute left-0 top-[40px] w-[200px] bg-white border border-gray-200 shadow-xl rounded-md py-1 z-[9999]" 
                 x-cloak>
                <div class="px-4 py-2 border-b border-gray-50 bg-gray-50/50">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Assigned Branch</p>
                </div>
                <a href="#" class="flex items-center px-4 py-2 text-[10px] font-bold text-blue-600 bg-blue-50/50 border-l-2 border-blue-600">
                    <i class="fa fa-check-circle mr-2"></i> MAIN BRANCH
                </a>
                <!-- Future branches can be added here by Super Admin -->
            </div>
        </div>
    </div>

    <!-- Right: Search and Menus -->
    <div class="flex items-center space-x-4 flex-1 justify-end pr-4">
        <!-- Global Search -->
        <div class="hidden lg:flex items-center bg-gray-50 border border-gray-300 rounded px-2 h-[34px] group focus-within:ring-1 focus-within:ring-blue-400 focus-within:border-blue-400 transition-all">
            <div class="flex items-center h-full border-r border-gray-300 pr-2 mr-2 cursor-pointer">
                <span class="text-[11px] font-bold text-gray-700">Ocean Export</span>
                <i class="fa fa-angle-down ml-2 text-gray-400"></i>
            </div>
            <input type="text" class="bg-transparent border-none text-[12px] w-48 focus:ring-0 placeholder-gray-400 font-medium" placeholder="Search...">
            <button class="text-gray-400 hover:text-blue-500">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="flex items-center space-x-1">
            {{-- Branch moved to left --}}
            
            <button class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full transition-colors relative">
                <i class="fa fa-list-ul"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
            </button>

            <button class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                <i class="fa fa-bell-o"></i>
            </button>
        </div>

        <!-- User Profile -->
        <div class="relative flex items-center space-x-2 pl-4 border-l border-gray-200 cursor-pointer group !overflow-visible mr-2" x-data="{ open: false }">
            <div @click="open = !open" class="flex items-center space-x-2">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="Avatar" class="w-8 h-8 rounded-full border border-gray-200">
                </div>
                <span class="hidden sm:inline text-[12px] font-bold text-gray-600 group-hover:text-blue-600 transition-colors uppercase">{{ Auth::user()->name }}</span>
                <i class="fa fa-angle-down text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </div>

            <!-- Premium Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1"
                 class="absolute right-0 top-[50px] w-[220px] bg-white border border-gray-200 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1),0_8px_10px_-6px_rgba(0,0,0,0.1)] rounded-md py-1 z-[9999]" 
                 x-cloak>
                <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Operator Account</p>
                    <p class="text-[12px] font-bold text-gray-800 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-[11px] font-bold text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                        <i class="fa fa-user-circle-o w-5 text-center mr-3 text-gray-400"></i> PROFILE SETTINGS
                    </a>
                   
                </div>

                <div class="border-t border-gray-100 mt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-[11px] font-black text-rose-600 hover:bg-rose-50 transition-colors">
                            <i class="fa fa-power-off w-5 text-center mr-3"></i> LOGOUT SYSTEM
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


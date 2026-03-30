<nav x-data="{ open: false, profileOpen: false }" class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-b border-slate-700 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Left: Logo & Links -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center group">
                    <a href="{{ route('admin.dashboard') }}" class="transition-transform duration-200 hover:scale-105">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg">
                                <i class="fas fa-cube text-white text-lg"></i>
                            </div>
                            <span class="text-xl font-bold text-white hidden sm:inline">Persia</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2
                       {{ request()->routeIs('admin.dashboard') 
                           ? 'bg-indigo-600 text-white shadow-lg' 
                           : 'text-slate-300 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- New Arrivals -->
                    <a href="{{ route('admin.newArrivals.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2
                       {{ request()->routeIs('admin.newArrivals.*') 
                           ? 'bg-indigo-600 text-white shadow-lg' 
                           : 'text-slate-300 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-star"></i>
                        <span>New Arrivals</span>
                    </a>

                    <!-- Collections -->
                    <a href="{{ route('admin.collections.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2
                       {{ request()->routeIs('admin.collections.*') 
                           ? 'bg-indigo-600 text-white shadow-lg' 
                           : 'text-slate-300 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-layer-group"></i>
                        <span>Collections</span>
                    </a>

                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center space-x-2
                       {{ request()->routeIs('admin.products.*') 
                           ? 'bg-indigo-600 text-white shadow-lg' 
                           : 'text-slate-300 hover:text-white hover:bg-slate-700/50' }}">
                        <i class="fas fa-boxes"></i>
                        <span>Products</span>
                    </a>
                </div>
            </div>

            <!-- Right: Settings & Profile -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Quick Stats -->
                <div class="flex items-center space-x-6 px-4 py-2 rounded-lg bg-slate-700/30 border border-slate-600/50">
                    <div class="text-center">
                        <div class="text-xs text-slate-400">User</div>
                        <div class="text-sm font-bold text-white">{{ Auth::user()->name }}</div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" 
                            class="inline-flex items-center space-x-2 px-4 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600 border border-slate-600 text-slate-300 hover:text-white font-medium transition-all duration-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': profileOpen}"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                         class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden">
                        
                        <!-- Header -->
                        <div class="px-5 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <!-- Profile -->
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center space-x-3 px-5 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                <i class="fas fa-user-circle text-indigo-600 w-4"></i>
                                <span class="text-sm font-medium">Edit Profile</span>
                            </a>

                            <!-- Settings -->
                            <a href="#" 
                               class="flex items-center space-x-3 px-5 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                <i class="fas fa-cog text-gray-600 w-4"></i>
                                <span class="text-sm font-medium">Settings</span>
                            </a>

                            <!-- Divider -->
                            <div class="my-2 border-t border-gray-100"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center space-x-3 px-5 py-3 text-red-600 hover:bg-red-50 transition-colors duration-150">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span class="text-sm font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center space-x-2">
                <!-- Mobile Profile Button -->
                <button @click="profileOpen = !profileOpen" 
                        class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                    <i class="fas fa-user-circle text-xl"></i>
                </button>

                <!-- Hamburger Menu -->
                <button @click="open = !open" 
                        class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'hidden': !open, 'block': open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Responsive Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-slate-800 border-t border-slate-700">
        
        <div class="px-4 py-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.dashboard') 
                   ? 'bg-indigo-600 text-white' 
                   : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span>Dashboard</span>
            </a>

            <!-- New Arrivals -->
            <a href="{{ route('admin.newArrivals.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.newArrivals.*') 
                   ? 'bg-indigo-600 text-white' 
                   : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                <i class="fas fa-star w-5"></i>
                <span>New Arrivals</span>
            </a>

            <!-- Collections -->
            <a href="{{ route('admin.collections.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.collections.*') 
                   ? 'bg-indigo-600 text-white' 
                   : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                <i class="fas fa-layer-group w-5"></i>
                <span>Collections</span>
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
               {{ request()->routeIs('admin.products.*') 
                   ? 'bg-indigo-600 text-white' 
                   : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                <i class="fas fa-boxes w-5"></i>
                <span>Products</span>
            </a>
        </div>
    </div>

    <!-- Mobile Profile Menu -->
    <div x-show="profileOpen && window.innerWidth < 768"
         x-transition
         class="md:hidden bg-slate-800 border-t border-slate-700 px-4 py-4">
        
        <div class="mb-4 pb-4 border-b border-slate-700">
            <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ Auth::user()->email }}</p>
        </div>

        <div class="space-y-2">
            <!-- Profile -->
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                <i class="fas fa-user-circle w-5"></i>
                <span class="text-sm font-medium">Edit Profile</span>
            </a>

            <!-- Settings -->
            <a href="#" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                <i class="fas fa-cog w-5"></i>
                <span class="text-sm font-medium">Settings</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-950/20 transition-colors">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="text-sm font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Styles -->
<style>
    /* Smooth transitions */
    [x-cloak] { display: none; }
    
    /* Active link indicator */
    .nav-active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, #4f46e5, #2563eb);
    }

    /* Navbar shadow on scroll */
    nav {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
</style>
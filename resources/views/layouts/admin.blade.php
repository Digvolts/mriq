<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) — Admin Panel</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet"/>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

<style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Inter', 'Figtree', sans-serif; }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar       { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: #1e293b; }
    ::-webkit-scrollbar-thumb { background: #475569; border-radius: 99px; }

    /* ── Sidebar link ── */
    .sidebar-link {
        transition: background .15s ease;
        white-space: nowrap;
        overflow: hidden;
    }
    .sidebar-link:hover  { background: rgba(255,255,255,.08); }
    .sidebar-link.active {
        background: #4f46e5;
        box-shadow: 0 4px 14px rgba(79,70,229,.4);
    }

    /* ── Tooltip ── */
    .sidebar-tooltip {
        pointer-events: none;
        opacity: 0;
        transform: translateX(-4px);
        transition: opacity .15s, transform .15s;
    }
    .sidebar-collapsed .sidebar-tooltip {
        pointer-events: auto;
    }
    .sidebar-link:hover .sidebar-tooltip {
        opacity: 1;
        transform: translateX(0);
    }

    /* ── Label & badge fade ── */
    .sidebar-label,
    .sidebar-badge,
    .sidebar-section-label {
        transition: opacity .2s ease, width .25s ease;
        overflow: hidden;
    }

    .sidebar-collapsed .sidebar-label,
    .sidebar-collapsed .sidebar-badge,
    .sidebar-collapsed .sidebar-section-label {
        opacity: 0;
        width: 0;
    }

    /* ── Logo text ── */
    .logo-text {
        transition: opacity .2s ease, width .25s ease, margin .25s ease;
        overflow: hidden;
        white-space: nowrap;
    }
    .sidebar-collapsed .logo-text {
        opacity: 0;
        width: 0;
        margin: 0;
    }

    /* ── Toggle icon rotate ── */
    .toggle-icon {
        transition: transform .25s ease;
    }
    .sidebar-collapsed .toggle-icon {
        transform: rotate(180deg);
    }

    /* ── User info ── */
    .user-info {
        transition: opacity .2s ease, width .25s ease;
        overflow: hidden;
        white-space: nowrap;
    }
    .sidebar-collapsed .user-info {
        opacity: 0;
        width: 0;
    }

    /* ── Pagination ── */
    nav[role="navigation"] .pagination {
        display: flex; gap: .25rem; margin: 0;
        padding: 0; list-style: none;
    }
    nav[role="navigation"] .page-link {
        padding: .375rem .75rem; border-radius: .5rem;
        font-size: .75rem; font-weight: 600; border: 0;
        color: #475569; background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,.06);
        transition: background .15s, color .15s;
    }
    nav[role="navigation"] .page-item.active .page-link {
        background: linear-gradient(135deg,#4f46e5,#2563eb); color: #fff;
    }
    nav[role="navigation"] .page-item.disabled .page-link {
        opacity: .4; cursor: not-allowed;
    }
</style>
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-800">

<div x-data="{
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        hoverExpand: false,
        userOpen: false,
        get expanded() {
            return !this.collapsed || this.hoverExpand;
        },
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', this.collapsed);
            if (!this.collapsed) this.hoverExpand = false;
        }
}" class="flex h-screen overflow-hidden">

    {{-- ════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════ --}}
    <aside
        @mouseenter="if (collapsed) hoverExpand = true"
        @mouseleave="hoverExpand = false"
        :class="!expanded ? 'w-[72px] sidebar-collapsed' : 'w-64'"
        class="flex-shrink-0 flex flex-col overflow-y-auto overflow-x-hidden relative
               transition-[width] duration-[250ms] ease-[cubic-bezier(.4,0,.2,1)]"
        style="background: linear-gradient(160deg,#0f172a 0%,#1e293b 55%,#0f172a 100%)"
    >

        {{-- ── Logo + Toggle ── --}}
        <div class="flex items-center border-b border-slate-700/60 h-16 px-3 gap-3">
            <a href="{{ route('admin.dashboard') }}"
               class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600
                      flex items-center justify-center shadow-lg flex-shrink-0
                      transition-transform hover:scale-105">
                <i class="fas fa-cube text-white text-lg"></i>
            </a>

            <span class="logo-text text-xl font-extrabold text-white tracking-tight flex-1">
                {{ config('app.name', 'Persia') }}
            </span>
        </div>

        {{-- ── Navigation ── --}}
        <nav class="flex-1 px-2 py-5 space-y-1">

            <p class="sidebar-section-label text-slate-500 text-[10px] font-bold
                      uppercase tracking-widest px-3 mb-3">
                Main Menu
            </p>

            @php
                try {
                    $__pendingCount = \App\Models\Order::where('payment_status','pending')->count();
                } catch(\Exception $e) {
                    $__pendingCount = 0;
                }

                $navItems = [
                    [
                        'route'  => 'admin.dashboard',
                        'match'  => 'admin.dashboard',
                        'icon'   => 'fas fa-chart-line',
                        'label'  => 'Dashboard',
                        'badge'  => null,
                    ],
                    [
                        'route'  => 'admin.newArrivals.index',
                        'match'  => 'admin.newArrivals.*',
                        'icon'   => 'fas fa-star',
                        'label'  => 'New Arrivals',
                        'badge'  => null,
                    ],
                    [
                        'route'  => 'admin.collections.index',
                        'match'  => 'admin.collections.*',
                        'icon'   => 'fas fa-layer-group',
                        'label'  => 'Collections',
                        'badge'  => null,
                    ],
                    [
                        'route'  => 'admin.products.index',
                        'match'  => 'admin.products.*',
                        'icon'   => 'fas fa-boxes',
                        'label'  => 'Products',
                        'badge'  => null,
                    ],
                    [
                        'route'  => 'admin.orders.index',
                        'match'  => 'admin.orders.*',
                        'icon'   => 'fas fa-shopping-cart',
                        'label'  => 'Orders',
                        'badge'  => $__pendingCount > 0 ? ($__pendingCount > 99 ? '99+' : $__pendingCount) : null,
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
            @php $isActive = request()->routeIs($item['match']); @endphp
            <div class="relative group/nav">
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl
                          text-sm font-medium text-slate-300
                          {{ $isActive ? 'active !text-white' : 'hover:text-white' }}">

                    <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                 {{ $isActive ? 'bg-white/20' : 'bg-slate-700/50' }}">
                        <i class="{{ $item['icon'] }} text-sm"></i>
                    </span>

                    <span class="sidebar-label flex-1 text-sm">{{ $item['label'] }}</span>

                    @if($item['badge'])
                    <span class="sidebar-badge bg-amber-400 text-amber-900 text-[10px]
                                 font-extrabold px-2 py-0.5 rounded-full shadow-sm flex-shrink-0">
                        {{ $item['badge'] }}
                    </span>
                    @endif
                </a>

                <div class="sidebar-tooltip fixed left-[76px] z-50
                            bg-slate-800 text-white text-xs font-semibold
                            px-3 py-1.5 rounded-lg shadow-xl border border-slate-700
                            flex items-center gap-2 pointer-events-none"
                     style="top: inherit; margin-top: -2.2rem;">
                    {{ $item['label'] }}
                    @if($item['badge'])
                    <span class="bg-amber-400 text-amber-900 text-[9px] font-extrabold
                                 px-1.5 py-0.5 rounded-full">
                        {{ $item['badge'] }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach

        </nav>

        {{-- ── User Footer ── --}}
        <div class="px-2 py-3 border-t border-slate-700/60">
            <button @click="userOpen = !userOpen"
                    class="w-full flex items-center gap-3 px-2 py-2.5 rounded-xl
                           hover:bg-slate-700/50 transition group">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500
                            flex items-center justify-center flex-shrink-0
                            font-bold text-white text-sm shadow">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>

                <div class="user-info flex-1 text-left min-w-0">
                    <p class="text-white text-xs font-semibold truncate">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-slate-500 text-[10px] truncate">
                        {{ Auth::user()->email ?? '' }}
                    </p>
                </div>

                <i class="fas fa-chevron-up text-slate-500 text-[10px] flex-shrink-0
                          sidebar-label transition-transform duration-200"
                   :class="{ 'rotate-180': userOpen }"></i>
            </button>

            <div x-show="userOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="mt-2 mx-1 bg-slate-800 rounded-xl border border-slate-700/60
                        overflow-hidden shadow-xl">

                <div class="px-4 py-3 border-b border-slate-700/60 block"
                     :class="collapsed && !hoverExpand ? 'block' : 'hidden'">
                    <p class="text-white text-xs font-semibold truncate">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-slate-500 text-[10px] truncate">
                        {{ Auth::user()->email ?? '' }}
                    </p>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-4 py-3 text-slate-300 text-sm
                          hover:bg-slate-700/60 hover:text-white transition-colors">
                    <i class="fas fa-user-circle text-indigo-400 w-4 text-center flex-shrink-0"></i>
                    <span class="font-medium">Edit Profile</span>
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 text-slate-300 text-sm
                          hover:bg-slate-700/60 hover:text-white transition-colors">
                    <i class="fas fa-cog text-slate-400 w-4 text-center flex-shrink-0"></i>
                    <span class="font-medium">Settings</span>
                </a>

                <div class="border-t border-slate-700/60"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 text-red-400 text-sm
                                   hover:bg-red-950/30 hover:text-red-300 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center flex-shrink-0"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ════════════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- ── Topbar ── --}}
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center
                       justify-between flex-shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="toggle()"
                        class="md:hidden w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200
                               flex items-center justify-center transition text-slate-500">
                    <i class="fas fa-bars text-sm"></i>
                </button>

                <div class="w-1 h-6 rounded-full bg-gradient-to-b from-indigo-500 to-blue-600
                            hidden md:block"></div>
                <div>
                    <h1 class="text-base font-bold text-slate-800 leading-tight">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    @hasSection('page_sub')
                    <p class="text-xs text-slate-400 mt-0.5">@yield('page_sub')</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 hidden sm:block">
                    <i class="far fa-calendar mr-1"></i>
                    {{ now()->translatedFormat('d F Y') }}
                </span>

                <div class="relative">
                    <button class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200
                                   flex items-center justify-center transition text-slate-500">
                        <i class="fas fa-bell text-sm"></i>
                    </button>
                    @if($__pendingCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white
                                 text-[9px] font-bold rounded-full flex items-center justify-center">
                        {{ $__pendingCount > 9 ? '9+' : $__pendingCount }}
                    </span>
                    @endif
                </div>
            </div>
        </header>

        {{-- ── Flash Messages ── --}}
        @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-init="setTimeout(() => show = false, 5000)"
             class="mx-6 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200
                    text-emerald-700 px-4 py-3 rounded-xl text-sm shadow-sm"
             role="alert">
            <i class="fas fa-circle-check flex-shrink-0 text-emerald-500"></i>
            <span class="flex-1">{!! session('success') !!}</span>
            <button @click="show = false" type="button"
                    class="text-emerald-400 hover:text-emerald-600 transition ml-auto flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-init="setTimeout(() => show = false, 5000)"
             class="mx-6 mt-4 flex items-center gap-3 bg-red-50 border border-red-200
                    text-red-700 px-4 py-3 rounded-xl text-sm shadow-sm"
             role="alert">
            <i class="fas fa-circle-xmark flex-shrink-0 text-red-500"></i>
            <span class="flex-1">{!! session('error') !!}</span>
            <button @click="show = false" type="button"
                    class="text-red-400 hover:text-red-600 transition ml-auto flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        @if(session('warning'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-init="setTimeout(() => show = false, 5000)"
             class="mx-6 mt-4 flex items-center gap-3 bg-amber-50 border border-amber-200
                    text-amber-700 px-4 py-3 rounded-xl text-sm shadow-sm"
             role="alert">
            <i class="fas fa-triangle-exclamation flex-shrink-0 text-amber-500"></i>
            <span class="flex-1">{!! session('warning') !!}</span>
            <button @click="show = false" type="button"
                    class="text-amber-400 hover:text-amber-600 transition ml-auto flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        {{-- ── Content ── --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false, expanded: false, sidebarExpanded: localStorage.getItem('sidebar_expanded') !== 'false' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Laundry Ibu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">

    {{-- Overlay untuk mobile --}}
    <div x-show="sidebarOpen" x-cloak 
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
        @click="sidebarOpen = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    {{-- Container utama flex h-screen --}}
    <div class="flex h-screen overflow-hidden">
        
        {{-- SIDEBAR --}}
        <aside 
            :class="(sidebarOpen ? 'translate-x-0' : '-translate-x-full') + ' ' + (sidebarExpanded || expanded ? 'w-64' : 'w-16')"
            @mouseenter="if (!sidebarExpanded && window.matchMedia('(hover: hover)').matches) expanded = true"
            @mouseleave="if (!sidebarExpanded && window.matchMedia('(hover: hover)').matches) expanded = false"
            class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-200 transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-x-hidden">
            
            <div class="flex-shrink-0 flex items-center h-14 border-b border-slate-200 px-4">
                <button @click="sidebarExpanded = !sidebarExpanded; expanded = false; localStorage.setItem('sidebar_expanded', sidebarExpanded)" 
                    class="flex items-center focus:outline-none cursor-pointer group relative">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!sidebarExpanded" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        <path x-show="sidebarExpanded" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-100" class="ml-3 text-lg font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent whitespace-nowrap">Laundry Ibu</span>
                    <div class="absolute left-full ml-3 px-2.5 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg whitespace-nowrap shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none z-50">
                        Toggle Sidebar
                    </div>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-2 py-2 space-y-0.5">
                <a href="/dashboard" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->is('dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Dashboard</span>
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'kasir']))
                <div class="pt-3 pb-0.5 flex items-center">
                    <div class="w-1 h-1 rounded-full bg-slate-300 mx-[19px] flex-shrink-0" x-show="!(sidebarExpanded || expanded)"></div>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out" class="px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Operasional</span>
                </div>
                <a href="{{ route('transaksi.index') }}" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->routeIs('transaksi.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Transaksi</span>
                </a>
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('paket.index') }}" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->routeIs('paket.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Paket</span>
                </a>
                @endif
                <a href="{{ route('member.index') }}" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->routeIs('member.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Pelanggan</span>
                </a>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'kasir', 'owner']))
                <div class="border-t border-slate-200 my-2 mx-2"></div>
                <div class="pt-1 pb-0.5 flex items-center">
                    <div class="w-1 h-1 rounded-full bg-slate-300 mx-[19px] flex-shrink-0" x-show="!(sidebarExpanded || expanded)"></div>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out" class="px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Laporan</span>
                </div>
                <a href="{{ route('report.index') }}" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->routeIs('report.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Laporan</span>
                </a>
                @endif

                @if(auth()->user()->role == 'admin')
                <div class="border-t border-slate-200 my-2 mx-2"></div>
                <div class="pt-1 pb-0.5 flex items-center">
                    <div class="w-1 h-1 rounded-full bg-slate-300 mx-[19px] flex-shrink-0" x-show="!(sidebarExpanded || expanded)"></div>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out" class="px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Manajemen</span>
                </div>
                <a href="{{ route('outlet.index') }}" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->routeIs('outlet.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Outlet</span>
                </a>
                <a href="{{ route('user.index') }}" 
                    class="flex items-center px-3 py-2.5 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 {{ request()->routeIs('user.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap">Pengguna</span>
                </a>
                @endif
            </nav>

            <div class="flex-shrink-0 border-t border-slate-200 px-2 py-3 flex flex-col gap-0.5">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                    class="flex items-center px-3 py-2.5 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarExpanded || expanded" x-transition:enter="transition ease-out delay-75" class="ml-3 text-sm whitespace-nowrap font-medium">Logout</span>
                </a>
            </div>
        </aside>

        {{-- AREA KANAN (Header + Main) --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- HEADER --}}
        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 flex-shrink-0">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-slate-500 lg:hidden focus:outline-none mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            {{-- Sisi Kanan: Profil User (Terdorong ke pojok kanan karena justify-between) --}}
            <div class="flex items-center space-x-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-700 leading-none mb-1">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-indigo-600 font-medium capitalize">{{ auth()->user()->role }}</p>
                </div>

                {{-- Inisial / Avatar --}}
                <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                <div class="max-w-7xl mx-auto">
                    <h1 class="text-2xl font-bold text-slate-800 mb-6">@yield('title')</h1>
                    
                    {{-- Area Notifikasi --}}
                <div class="max-w-7xl mx-auto px-4 mb-4">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="flex items-center p-4 mb-4 text-emerald-800 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm transition-all duration-500">
                            <span class="font-semibold text-sm">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" 
                            class="flex items-center justify-between p-4 mb-4 text-red-800 bg-red-50 border border-red-100 rounded-2xl shadow-sm">
                            <span class="font-semibold text-sm">{{ session('error') }}</span>
                            <button @click="show = false" class="text-red-500 hover:text-red-700 font-bold">×</button>
                        </div>
                    @endif

                    @if ($errors->any())
    <div x-data="{ show: true }" x-show="show" class="mb-4">
        <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <span class="font-medium">Ada beberapa kesalahan. Periksa input yang merah.</span>
            <button @click="show = false" class="text-red-400 hover:text-red-600 font-bold ml-2">×</button>
        </div>
    </div>
@endif
                </div>

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('footer')
</body>
</html>
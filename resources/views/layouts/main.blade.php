<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">
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
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0">
            
            <div class="flex items-center justify-center h-16 border-b border-slate-100">
                <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">
                    Laundry Ibu
                </span>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                <a href="/dashboard" 
                    class="flex items-center px-4 py-3 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all duration-200 {{ request()->is('dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <span class="ml-3">Dashboard</span>
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'kasir']))
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Operasional</span>
                </div>
                <a href="{{ route('member.index') }}" 
                    class="flex items-center px-4 py-3 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all duration-200 {{ request()->routeIs('member.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <span class="ml-3">Pelanggan</span>
                </a>
                @endif

                @if(auth()->user()->role == 'admin')
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manajemen</span>
                </div>
                <a href="{{ route('outlet.index') }}" 
                    class="flex items-center px-4 py-3 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all duration-200 {{ request()->routeIs('outlet.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <span class="ml-3">Outlet</span>
                </a>
                <a href="{{ route('user.index') }}" 
                    class="flex items-center px-4 py-3 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all duration-200 {{ request()->routeIs('user.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : '' }}">
                    <span class="ml-3">Pengguna</span>
                </a>
                @endif

                <div class="pt-10">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                        class="flex items-center px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200">
                        <span class="ml-3 font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        {{-- AREA KANAN (Header + Main) --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- HEADER --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 flex-shrink-0">
                <button @click="sidebarOpen = true" class="text-slate-500 lg:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                <div class="max-w-7xl mx-auto">
                    <h1 class="text-2xl font-bold text-slate-800 mb-6">@yield('title')</h1>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</body>
</html>
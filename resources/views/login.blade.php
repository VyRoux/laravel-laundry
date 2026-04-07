<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laundry Ibu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes float {
            0% { transform: translate(0, 0); }
            50% { transform: translate(30px, 50px); }
            100% { transform: translate(0, 0); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 overflow-hidden font-sans">

    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-indigo-200/50 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-[10%] right-[-5%] w-80 h-80 bg-blue-200/50 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-[40%] right-[20%] w-64 h-64 bg-purple-200/40 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div x-data="{ loading: false }" class="w-full max-w-md">
            
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">
                        Laundry Ibu
                    </h1>
                    <p class="text-slate-500 text-sm mt-2">Silahkan masuk ke akun anda</p>
                </div>

                @if(session('loginError'))
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm font-medium">
                    {{ session('loginError') }}
                </div>
                @endif

                <form action="/login" method="POST" @submit="loading = true" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" 
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all bg-white/50" 
                                placeholder="Masukkan username" required autofocus>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" 
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all bg-white/50" 
                                placeholder="••••••••" required>
                    </div>

                    <div class="flex items-center justify-between px-1">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
                    </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center space-x-2">
                        <span x-show="!loading">Masuk Sekarang</span>
                        <span x-show="loading" x-cloak class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-400">© 2026 Laundry Ibu | Ghif Project</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
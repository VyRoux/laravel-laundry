<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laundry Ibu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .ball {
            position: absolute;
            border-radius: 50%;
            z-index: -1;
            filter: blur(40px);
        }
    </style>
</head>
<body class="bg-slate-100 h-screen w-screen overflow-hidden flex items-center justify-center relative font-sans">

    <div id="ball1" class="ball w-64 h-64 bg-indigo-300 opacity-40" style="top: 10%; left: 15%;"></div>
    <div id="ball2" class="ball w-96 h-96 bg-blue-300 opacity-30" style="bottom: 5%; right: 10%;"></div>
    <div id="ball3" class="ball w-48 h-48 bg-purple-300 opacity-40" style="top: 50%; left: 60%;"></div>

    <div class="w-full max-w-md px-6">
        <div class="glass p-8 rounded-3xl shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent inline-block">
                    Laundry Ibu
                </h1>
                <p class="text-slate-500 text-sm mt-2">Selamat datang kembali, silakan login.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-semibold mb-4 border border-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" 
                           class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all bg-white/50" 
                           placeholder="Masukkan username" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1 ml-1">Password</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all bg-white/50" 
                           placeholder="••••••••" required>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-indigo-200 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Laundry Ibu - Ghif Project
            </div>
        </div>
    </div>

    <script>
        // Animasi Bola Bergerak Smooth pakai Anime.js
        function animateBalls() {
            anime({
                targets: '.ball',
                translateX: function() { return anime.random(-50, 50) + 'vh'; },
                translateY: function() { return anime.random(-50, 50) + 'vh'; },
                scale: function() { return anime.random(1, 1.5); },
                duration: function() { return anime.random(10000, 20000); },
                delay: function() { return anime.random(0, 1000); },
                easing: 'easeInOutQuad',
                complete: animateBalls
            });
        }
        animateBalls();
    </script>
</body>
</html>
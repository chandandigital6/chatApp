<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Real Victory Groups</title>

    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Keep your existing big Tailwind fallback here -->
        <style>/* your inline Tailwind fallback CSS */</style>
    @endif
</head>
<body class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center p-6">

    <main class="w-full max-w-sm">
        <!-- Card -->
        <div class="relative overflow-hidden rounded-2xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#111] shadow-[0_8px_30px_rgba(0,0,0,0.06)]">
            <!-- Subtle gradient glow -->
            <div class="pointer-events-none absolute inset-0 opacity-70"
                 style="background: radial-gradient(500px 220px at 10% -10%, rgba(99,102,241,0.10), transparent 60%),
                         radial-gradient(420px 200px at 110% 10%, rgba(16,185,129,0.10), transparent 60%);"></div>

            <div class="relative p-6 space-y-5">
                <!-- Brand -->
                <div class="flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('logo.png') }}" alt="RVG"
                         class="h-12 w-12 rounded-full ring-1 ring-black/10 dark:ring-white/10">
                    <div>
                        <h1 class="text-xl font-semibold">Welcome to Real Victory Groups</h1>
                        <p class="mt-1 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Sign in or create your account to continue.
                        </p>
                    </div>
                </div>

                @if (Route::has('login'))
                    <div class="space-y-3">
                        @guest
                            <!-- Log in -->
                           <a href="{{ route('login') }}"
   class="block w-full text-center rounded-xl px-4 py-2.5 bg-black text-white 
          border border-black/15 dark:border-white/15 
          hover:bg-gray-800 dark:hover:bg-gray-200 dark:hover:text-black
          transition focus:outline-none focus:ring-2 focus:ring-black/30 dark:focus:ring-white/30">
    Log in
</a>
    
                            @if (Route::has('register'))
                                <!-- Create account -->
                                <a href="{{ route('register') }}"
                                   class="block w-full text-center rounded-xl px-4 py-2.5 bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-black/30 dark:focus:ring-white/30">
                                    Create account
                                </a>
                            @endif
                        @endguest

                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="block w-full text-center rounded-xl px-4 py-2.5 bg-black text-white dark:bg-white dark:text-black hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-black/30 dark:focus:ring-white/30">
                                Go to Dashboard
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <!-- Tiny footer (optional) -->
        <p class="mt-4 text-center text-xs text-[#706f6c] dark:text-[#A1A09A]">
            © {{ date('Y') }} Real Victory Groups
        </p>
    </main>

</body>
</html>

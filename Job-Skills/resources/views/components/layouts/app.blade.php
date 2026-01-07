<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JobSkills') }} — Trouvez votre prochaine aventure</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --brand-primary: #184085;
            --brand-secondary: #0ea5e9;
            --bg-main: #f8fafc;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-main);
            color: #1e293b;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Modern Buttons */
        .btn-modern {
            @apply inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 active:scale-[0.98];
        }

        .btn-modern-primary {
            background-color: var(--brand-primary);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(24, 64, 133, 0.2);
        }

        .btn-modern-primary:hover {
            @apply opacity-90 -translate-y-0.5 shadow-lg;
            box-shadow: 0 20px 25px -5px rgba(24, 64, 133, 0.1);
        }

        .btn-modern-secondary {
            @apply bg-white text-slate-700 border border-slate-200;
        }

        .btn-modern-secondary:hover {
            @apply bg-slate-50 border-slate-300 -translate-y-0.5;
        }

        /* Modern Inputs */
        .input-modern {
            @apply w-full px-4 py-3 bg-white border border-slate-200 rounded-xl transition-all duration-300 outline-none;
        }

        .input-modern:focus {
            @apply border-sky-500 ring-4 ring-sky-500/10;
        }

        /* Badges */
        .badge-modern {
            @apply px-3 py-1 rounded-full text-xs font-semibold tracking-wide uppercase;
        }

        /* Gradient Text */
        .text-gradient {
            @apply bg-clip-text text-transparent bg-gradient-to-r from-blue-900 to-sky-600;
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-100 selection:text-blue-900">
    <!-- Navigation -->
    <header class="glass-header sticky top-0 z-50 border-b border-slate-200/60">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a class="text-2xl font-bold tracking-tight flex items-center gap-2 group" href="{{ url('/') }}">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-700 to-sky-500 flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2H7a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-slate-900">Job<span class="text-blue-700">Skills</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a class="text-sm font-medium text-slate-600 hover:text-blue-700 transition-colors" href="{{ route('emplois.index') }}">Explorer les offres</a>
                
                @auth
                    <a class="text-sm font-medium text-slate-600 hover:text-blue-700 transition-colors" href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-500 transition-colors">Déconnexion</button>
                    </form>
                @else
                    <a class="text-sm font-medium text-slate-600 hover:text-blue-700 transition-colors" href="{{ route('login') }}">Connexion</a>
                    <a class="btn-modern btn-modern-primary !py-2 !px-5 !text-sm" href="{{ route('register') }}">Commencer</a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle (Simplified) -->
            <button class="md:hidden text-slate-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>
        </nav>
    </header>

    <!-- Page Content -->
    <main class="relative">
        <!-- Background Decorations -->
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none -z-10 overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-100/50 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[30%] h-[30%] bg-sky-100/50 blur-[120px] rounded-full"></div>
        </div>
        
        {{ $slot }}
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a class="text-2xl font-bold tracking-tight flex items-center gap-2 mb-6" href="{{ url('/') }}">
                        <div class="w-8 h-8 rounded-lg bg-blue-700 flex items-center justify-center text-white">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745"/>
                            </svg>
                        </div>
                        <span class="text-slate-900">JobSkills</span>
                    </a>
                    <p class="text-slate-500 max-w-sm leading-relaxed">
                        Le pont entre les meilleurs talents et les entreprises les plus innovantes. Notre mission est de simplifier votre recherche d'emploi.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6 uppercase text-xs tracking-widest">Navigation</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('emplois.index') }}" class="text-slate-500 hover:text-blue-700 text-sm transition-colors">Offres d'emploi</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-700 text-sm transition-colors">Entreprises</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-700 text-sm transition-colors">À propos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6 uppercase text-xs tracking-widest">Légal</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-500 hover:text-blue-700 text-sm transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-700 text-sm transition-colors">Conditions d'utilisation</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 text-center">
                <p class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} JobSkills Platform. Conçu avec excellence.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>

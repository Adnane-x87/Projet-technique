<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JobSkills') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800 font-sans antialiased">
    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <nav class="max-w-[85rem] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a class="flex items-center gap-2 text-xl font-bold text-gray-800 hover:text-blue-600 transition-colors"
                href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="JobSkills Logo" class="h-10 w-auto">
                <span>JobSkills</span>
            </a>
            <div class="flex items-center gap-6">
                @auth
                    <div class="flex items-center gap-4">

                        @can('access-admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                                Tableau de bord
                            </a>
                        @endcan

                        <div class="text-right">
                            @if (Auth::user()->is_admin)
                                <span
                                    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Admin
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    User
                                </span>
                            @endif
                            <p class="text-sm font-semibold text-gray-800 mt-1">{{ Auth::user()->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 border border-transparent hover:border-gray-200 rounded-lg transition-colors duration-200">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            S'inscrire
                        </a>
                    </div>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen bg-gray-50">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-10 mt-auto">
        <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Adnane. Tous droits réservés.</p>
        </div>
    </footer>
    </footer>

    @stack('scripts')
</body>

</html>

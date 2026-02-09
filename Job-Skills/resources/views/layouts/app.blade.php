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
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <nav class="max-w-[85rem] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a class="flex items-center gap-2 text-xl font-bold text-gray-800 hover:text-blue-600 transition-colors"
                href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="JobSkills Logo" class="h-10 w-auto">
                <span>JobSkills</span>
            </a>

            <div class="flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600">Register</a>
                @else
                    <span class="text-sm text-gray-500">Hello, {{ Auth::user()->name }}</span>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">Logout</button>
                    </form>
                @endguest
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
</body>

</html>

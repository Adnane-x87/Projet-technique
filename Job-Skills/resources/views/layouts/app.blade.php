<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JobSkills') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <nav class="max-w-[85rem] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a class="flex items-center gap-2 text-xl font-bold text-gray-800 hover:text-blue-600 transition-colors" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="JobSkills Logo" class="h-10 w-auto">
                <span>JobSkills</span>
            </a>
            <div class="flex items-center gap-6 font-medium text-gray-600">
                <a href="{{ route('emplois.index') }}" class="hover:text-blue-600 transition-colors">Offres</a>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-10 mt-auto">
        <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Adnane. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>

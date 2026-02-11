<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JobSkills - Dashboard Admin</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 font-sans">
    <!-- Main Content -->
    <main id="content" role="main" class="w-full min-h-screen">
        @yield('content')
    </main>

    @stack('scripts')

    <script>
        window.addEventListener('load', () => {
            if (window.lucide && window.lucide.createIcons && window.lucide.icons) {
                window.lucide.createIcons({
                    icons: window.lucide.icons
                });
            }
        });
    </script>
</body>

</html>

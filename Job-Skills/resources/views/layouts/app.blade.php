<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Job Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-8">
    <div class="max-w-4xl mx-auto">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
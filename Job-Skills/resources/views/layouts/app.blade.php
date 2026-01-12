<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JobSkills') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        a {
            color: #0066cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            color: #555;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #f0f0f0;
            text-decoration: none;
        }

        .btn-primary {
            background: #0066cc;
            color: #fff;
            border-color: #0066cc;
        }

        .btn-primary:hover {
            background: #0055aa;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        /* Forms */
        input,
        select,
        textarea {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            width: 100%;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #0066cc;
        }

        /* Cards */
        /* Cards */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            border: none;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Footer */
        footer {
            background: #333;
            color: #fff;
            padding: 30px 0;
            margin-top: 40px;
            text-align: center;
        }

        footer a {
            color: #aaa;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="container">
            <a class="logo" href="{{ url('/') }}"
                style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit;">
                <img src="{{ asset('images/logo.png') }}" alt="JobSkills Logo"
                    style="height: 48px; width: auto; object-fit: contain;">
                <span style="font-size: 1.5rem; font-weight: bold; color: #333;">JobSkills</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('emplois.index') }}">Offres</a>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container" style="padding: 30px 20px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} JobSkills. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>

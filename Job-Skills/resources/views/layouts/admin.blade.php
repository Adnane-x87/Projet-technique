<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSkills - Dashboard Admin</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 font-sans">
    <!-- Sidebar -->
    <div id="application-sidebar"
        class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 left-0 bottom-0 z-[60] w-64 bg-white border-r border-gray-200 pt-7 pb-10 overflow-y-auto scrollbar-y lg:block lg:translate-x-0 lg:right-auto lg:bottom-0">
        <div class="px-6">
            <a class="flex items-center gap-x-2 text-xl font-bold text-gray-900" href="{{ url('/') }}"
                aria-label="JobSkills">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span>Job<span class="text-blue-600">Skills</span></span>
            </a>
        </div>

        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1 flex flex-col h-full">
                <!-- Dashboard -->
                <li>
                    <a class="flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm font-medium focus:outline-none {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                        href="{{ route('admin.dashboard') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- Divider -->
                <li class="my-4 border-t border-gray-100"></li>

                <!-- Content Section -->
                <li class="mb-2 px-3 text-xs uppercase tracking-wider text-gray-400 font-semibold">
                    Contenu
                </li>

                <li>
                    <a class="flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm font-medium focus:outline-none {{ request()->routeIs('emplois.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                        href="{{ route('emplois.index') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Offres Public
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- End Sidebar -->

    <!-- Navbar -->
    <header
        class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b border-gray-200 text-sm py-3 sm:py-4 lg:pl-64">
        <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6 md:px-8" aria-label="Global">
            <div class="mr-5 lg:mr-0 lg:hidden">
                <a class="flex items-center gap-x-2 text-xl font-semibold text-gray-900" href="#"
                    aria-label="Brand">
                    <span class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <span>Job<span class="text-blue-600">Skills</span></span>
                </a>
            </div>

            <div class="w-full flex items-center justify-end ml-auto sm:justify-between sm:gap-x-3 sm:order-3">
                <div class="hidden sm:block"></div>

                <div class="flex flex-row items-center justify-end gap-2">
                    <div class="relative items-center inline-flex" data-hs-dropdown-placement="bottom-right">
                        <button id="hs-dropdown-with-header" type="button"
                            class="hs-dropdown-toggle inline-flex justify-center items-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                A
                            </div>
                        </button>
                        <div class="absolute right-0 top-full mt-2 z-50 transition-[opacity,margin] duration opacity-0 hidden min-w-[15rem] bg-white shadow-lg rounded-xl border border-gray-100 p-2"
                            aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-4 -m-2 bg-gray-50 rounded-t-lg border-b border-gray-100">
                                <p class="text-xs text-gray-500">Connecté en tant que</p>
                                <p class="text-sm font-semibold text-gray-800">Admin</p>
                            </div>
                            <div class="mt-2 py-2">
                                <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    href="#">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Se déconnecter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main id="content" role="main" class="w-full pt-8 px-4 sm:px-6 md:px-8 lg:pl-72 pb-10">
        @yield('content')
    </main>
    <!-- End Main Content -->

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

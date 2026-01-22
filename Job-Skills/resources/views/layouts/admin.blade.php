<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSkills - Dashboard Admin</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-zinc-950 text-gray-900 dark:text-zinc-100 font-sans">
    <!-- Sidebar -->
    <div id="application-sidebar"
        class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 left-0 bottom-0 z-[60] w-64 bg-white border-r border-gray-200 pt-7 pb-10 overflow-y-auto scrollbar-y lg:block lg:translate-x-0 lg:right-auto lg:bottom-0 dark:scrollbar-y dark:bg-zinc-950 dark:border-zinc-800">
        <div class="px-6">
            <a class="flex items-center gap-x-2 text-xl font-bold dark:text-white" href="{{ url('/') }}"
                aria-label="JobSkills">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-md">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <span>Job<span class="text-blue-600">Skills</span></span>
            </a>
        </div>

        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1.5 flex flex-col h-full">
                <!-- Group 1: Main -->
                <li>
                    <a class="flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm font-medium focus:outline-none {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-zinc-400 dark:hover:bg-zinc-900 dark:hover:text-white' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i data-lucide="layout-dashboard" class="w-[18px] h-[18px]"></i>
                        Dashboard
                    </a>
                </li>

                <!-- Spacer -->
                <li class="my-4 border-t border-gray-100 dark:border-zinc-800"></li>

                <!-- Group 2: Content -->
                <li class="mb-2 px-3 text-[10px] uppercase tracking-widest text-gray-400 font-bold font-heading">
                    Contenu
                </li>

                <li>
                    <a class="flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm font-medium focus:outline-none {{ request()->routeIs('emplois.index') ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-zinc-400 dark:hover:bg-zinc-900 dark:hover:text-white' }}"
                        href="{{ route('emplois.index') }}">
                        <i data-lucide="briefcase" class="w-[18px] h-[18px]"></i>
                        Offres Public
                    </a>
                </li>


            </ul>
        </nav>
    </div>
    <!-- End Sidebar -->

    <!-- Navbar -->
    <header
        class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:pl-64 dark:bg-zinc-950 dark:border-zinc-800">
        <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6 md:px-8" aria-label="Global">
            <div class="mr-5 lg:mr-0 lg:hidden">
                <a class="flex items-center gap-x-2 text-xl font-semibold dark:text-white" href="#" aria-label="Brand">
                    <span class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-md">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </span>
                    <span>Job<span class="text-blue-600">Skills</span></span>
                </a>
            </div>

            <div class="w-full flex items-center justify-end ml-auto sm:justify-between sm:gap-x-3 sm:order-3">
                <div class="hidden sm:block">
                </div>

                <div class="flex flex-row items-center justify-end gap-2">
                    <div class="relative items-center inline-flex" data-hs-dropdown-placement="bottom-right">
                        <button id="hs-dropdown-with-header" type="button"
                            class="hs-dropdown-toggle inline-flex justify-center items-center gap-2 w-[2.375rem] h-[2.375rem] rounded-full border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-xs dark:bg-zinc-950 dark:border-zinc-800 dark:text-white dark:hover:bg-zinc-900 dark:focus:ring-zinc-800 dark:focus:ring-offset-zinc-900">
                            <!-- Placeholder Avatar -->
                            <div
                                class="w-[2.375rem] h-[2.375rem] rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                A
                            </div>
                        </button>
                        <div class="absolute right-0 top-full mt-2 z-50 transition-[opacity,margin] duration opacity-0 hidden min-w-[15rem] bg-white shadow-md rounded-lg p-2 dark:bg-zinc-900 dark:border dark:border-zinc-800"
                            aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-zinc-800">
                                <p class="text-sm text-gray-500 dark:text-zinc-400">Connecté en tant que</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-zinc-300">Admin</p>
                            </div>
                            <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-md text-sm text-red-600 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-red-500 dark:hover:bg-zinc-800 dark:hover:text-red-400"
                                    href="#">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
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
    <main id="content" role="main" class="w-full pt-10 px-4 sm:px-6 md:px-8 lg:pl-72">
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

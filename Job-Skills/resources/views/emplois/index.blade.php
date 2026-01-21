@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between border-b border-gray-200 dark:border-gray-700 pb-5">
            <div>
                <h1 class="text-2xl font-bold font-heading text-gray-800 dark:text-white">Offres d'emploi</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Explorez nos opportunités professionnelles</p><br>
            </div>
            <div>
                <!-- Optional: Add button or action here if needed -->
            </div>
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
            <form action="{{ route('emplois.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Rechercher par titre, entreprise..."
                        class="w-full py-2.5 pl-10 pr-4 text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
                <div class="md:w-64">
                     <select name="skill" id="skill" 
                        class="w-full py-2.5 px-4 text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                        <option value="">Toutes les compétences</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                                {{ $skill->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div><br>

        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-slate-900 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Poste & Entreprise</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Compétences</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Publiée</th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @include('emplois._table_body')
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $emplois->withQueryString()->links() }}
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const skillSelect = document.getElementById('skill');
            const tableBody = document.querySelector('tbody');

            function fetchJobs() {
                const search = searchInput.value;
                const skill = skillSelect.value;
                const url = new URL(window.location.href);
                
                if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
                if (skill) url.searchParams.set('skill', skill); else url.searchParams.delete('skill');

                url.searchParams.set('page', 1);

                window.history.pushState({}, '', url);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text())
                    .then(html => {
                        tableBody.innerHTML = html;
                        if (window.lucide) window.lucide.createIcons();
                    });
            }

            function debounce(func, timeout = 300) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => { func.apply(this, args); }, timeout);
                };
            }

            if (searchInput) {
                searchInput.addEventListener('input', debounce(fetchJobs, 500));
            }
            if (skillSelect) {
                skillSelect.addEventListener('change', fetchJobs);
            }
        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-[85rem] px-4 py-16 sm:px-6 lg:px-8 mx-auto">
    <!-- Header -->
    <div class="max-w-2xl mx-auto text-center mb-12">
        <h2 class="text-3xl font-extrabold text-black mb-3 dark:text-white">Offres Récentes</h2>
        <p class="text-lg text-gray-700 dark:text-zinc-400">Découvrez les dernières opportunités de la communauté.</p>
    </div>

    <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-sm p-3 mb-10 max-w-4xl mx-auto dark:bg-zinc-950 dark:border-zinc-800">
        <div class="flex flex-col md:flex-row items-stretch gap-2">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input type="text" id="search-input" value="{{ request('search') }}"
                    class="w-full py-3.5 pl-11 pr-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm placeholder-gray-400 dark:bg-zinc-900 dark:border-zinc-800 dark:text-white"
                    placeholder="Rechercher un poste, une entreprise...">
            </div>
            
            <!-- Skill Filter -->
            <div class="w-full md:w-56">
                <select id="skill-filter" 
                    class="w-full py-3.5 px-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 text-sm cursor-pointer dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-300">
                    <option value="">Toutes les compétences</option>
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                            {{ $skill->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filter Action -->
            <button onclick="applyFilters()" class="bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-md active:scale-95 whitespace-nowrap">
                Filtrer
            </button>
        </div>
    </div>

    <!-- Grid Container -->
    <div id="jobs-grid-container">
        <div id="jobs-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @include('emplois._job_card')
        </div>

        <!-- Pagination -->
        <div id="pagination-links" class="mt-12 flex justify-center">
            {{ $emplois->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-input');
        const skillSelect = document.getElementById('skill-filter');
        const grid = document.getElementById('jobs-grid');

        function fetchJobs() {
            const search = searchInput.value;
            const skill = skillSelect.value;
            const url = new URL('{{ route('emplois.index') }}');
            
            if (search) url.searchParams.set('search', search);
            if (skill) url.searchParams.set('skill', skill);
            url.searchParams.set('page', 1);

            window.history.pushState({}, '', url);

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                grid.innerHTML = html;
                if (window.lucide) window.lucide.createIcons();
            })
            .catch(err => console.error('Error fetching jobs:', err));
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
        
        window.applyFilters = fetchJobs;
    });
</script>
@endpush
@endsection

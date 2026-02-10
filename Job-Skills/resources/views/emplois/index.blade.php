@extends('layouts.app')

@section('content')
    <div class="max-w-[85rem] px-4 py-12 sm:px-6 lg:px-8 mx-auto">

        <!-- Header -->
        <div class="max-w-2xl mx-auto text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Offres Récentes</h2>
            <p class="text-gray-500">Découvrez les dernières opportunités de la communauté.</p>
        </div>

        <!-- Search & Filter (50% / 50%) -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-10 max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row gap-4">

                <!-- LEFT: Search (50%) -->
                <div class="w-full md:w-1/2 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input
                        type="text"
                        id="search-input"
                        value="{{ request('search') }}"
                        class="w-full py-2.5 pl-10 pr-4 bg-gray-50 border border-gray-200 rounded-lg
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               text-gray-900 text-sm placeholder-gray-400"
                        placeholder="Rechercher un poste, une entreprise..."
                    >
                </div>

                <!-- RIGHT: Filters (50%) -->
                <div class="w-full md:w-1/2 flex gap-3">

                    <!-- Skill Filter -->
                    <div class="flex-1">
                        <select
                            id="skill-filter"
                            class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   text-gray-700 text-sm cursor-pointer"
                        >
                            <option value="">Toutes les compétences</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <button
                        onclick="applyFilters()"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-lg
                               font-medium text-sm hover:bg-blue-700 transition-colors whitespace-nowrap"
                    >
                        Rechercher
                    </button>
                </div>
            </div>
        </div>

        <!-- Jobs Grid -->
        <div id="jobs-grid-container">
            <div id="jobs-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('emplois._job_card')
            </div>

            <!-- Pagination -->
            <div id="pagination-links" class="mt-10 flex justify-center">
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
                    const url = new URL('{{ route('emplois.index') }}');

                    if (searchInput.value) {
                        url.searchParams.set('search', searchInput.value);
                    }
                    if (skillSelect.value) {
                        url.searchParams.set('skill', skillSelect.value);
                    }
                    url.searchParams.set('page', 1);

                    window.history.pushState({}, '', url);

                    fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.text())
                        .then(html => {
                            grid.innerHTML = html;
                        })
                        .catch(err => console.error(err));
                }

                function debounce(fn, delay = 400) {
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => fn.apply(this, args), delay);
                    };
                }

                searchInput.addEventListener('input', debounce(fetchJobs));
                skillSelect.addEventListener('change', fetchJobs);

                window.applyFilters = fetchJobs;
            });
        </script>
    @endpush
@endsection

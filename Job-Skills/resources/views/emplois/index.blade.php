@extends('layouts.app')

@section('content')
    <div x-data="publicJobSearch()" x-init="init()" class="max-w-[85rem] px-4 py-12 sm:px-6 lg:px-8 mx-auto">

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
                        x-model="search"
                        @input.debounce.500ms="fetchJobs"
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
                            x-model="skill"
                            @change="fetchJobs"
                            class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-lg
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   text-gray-700 text-sm cursor-pointer"
                        >
                            <option value="">Toutes les compétences</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->id }}">
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <button
                        @click="fetchJobs"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-lg
                               font-medium text-sm hover:bg-blue-700 transition-colors whitespace-nowrap"
                    >
                        Rechercher
                    </button>
                    <button
                        @click="resetFilters"
                        class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg
                               font-medium text-sm hover:bg-gray-200 transition-colors whitespace-nowrap"
                    >
                        Effacer
                    </button>
                </div>
            </div>
        </div>

        <!-- Jobs Grid -->
        <div id="jobs-grid-container">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="job in jobs" :key="job.id">
                    <a :href="job.url" class="group flex flex-col bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all overflow-hidden h-full">
                        <div class="aspect-video relative overflow-hidden bg-slate-50 border-b border-gray-100">
                             <template x-if="job.image">
                                <img :src="'/storage/' + job.image" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" :alt="job.company">
                             </template>
                             <template x-if="!job.image">
                                <div class="flex items-center justify-center h-full bg-slate-50">
                                    <svg class="size-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                             </template>

                            <div class="absolute top-3 right-3 text-right">
                                <template x-if="job.skills.length > 0">
                                    <span class="py-1 px-3 bg-white/90 backdrop-blur shadow-sm rounded-full text-[10px] font-bold text-blue-600 uppercase tracking-widest" x-text="job.skills[0].name"></span>
                                </template>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors" x-text="job.title">
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 line-clamp-2" x-text="job.description">
                            </p>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-x-2">
                                    <div class="size-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600 line-clamp-1" x-text="job.company"></span>
                                </div>
                                <div class="flex items-center text-blue-600 font-semibold text-sm">
                                    Voir détails
                                    <svg class="size-4 ms-1 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
            
             <template x-if="jobs.length === 0">
                <div class="text-center py-12">
                   <p class="text-gray-500 text-lg">Aucune offre trouvée.</p>
                </div>
             </template>

            <!-- Pagination logic not fully connected in this snippet for brevity, but would work similarly via fetch updates or simple links if full page reload preferred for pagination. Here we assume infinite scroll or load more pattern or just list all for simplicity, or we can keep the laravel links for initial load and hide them on search. For now, hiding standard pagination if search is active is common pattern. -->
             <div x-show="!search && !skill" class="mt-10 flex justify-center">
                {{ $emplois->withQueryString()->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function publicJobSearch() {
                return {
                    jobs: @json($emplois->items()).map(job => ({...job, url: `/emplois/${job.id}`})),
                    search: '{{ request('search') }}',
                    skill: '{{ request('skill') }}',

                    init() {
                        // If there are initial query params, we might want to ensure state matches, but blade rendering handles initial state.
                    },

                    fetchJobs() {
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.skill) params.append('skill', this.skill);

                        // Update URL without reload
                        const newUrl = window.location.pathname + '?' + params.toString();
                        window.history.pushState({}, '', newUrl);

                        fetch('/api/emplois?' + params.toString())
                            .then(res => res.json())
                            .then(data => {
                                this.jobs = data.emplois;
                            });
                    },
                    
                    resetFilters() {
                        this.search = '';
                        this.skill = '';
                        this.fetchJobs();
                    }
                }
            }
        </script>
    @endpush
@endsection


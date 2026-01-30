@extends('layouts.app')

@section('content')
    <div class="max-w-[85rem] px-4 py-12 sm:px-6 lg:px-8 mx-auto" x-data="{
        search: '{{ request('search') }}',
        skill: '{{ request('skill') }}',
        jobs: [],
        loading: false,
        useAjax: false,
        baseUrl: '{{ route('emplois.index') }}',
    
        async fetchJobs() {
            this.loading = true;
            this.useAjax = true;
    
            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.skill) params.append('skill', this.skill);
    
            // Update URL
            const url = new URL(this.baseUrl);
            if (this.search) url.searchParams.set('search', this.search);
            if (this.skill) url.searchParams.set('skill', this.skill);
            window.history.pushState({}, '', url);
    
            try {
                const response = await fetch('/api/emplois?' + params.toString());
                const data = await response.json();
                this.jobs = data.emplois || [];
            } catch (err) {
                console.error('Error fetching jobs:', err);
            } finally {
                this.loading = false;
            }
        },
    
        resetFilters() {
            this.search = '';
            this.skill = '';
            window.location.href = this.baseUrl;
        }
    }">

        <!-- Header -->
        <div class="max-w-2xl mx-auto text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Offres Récentes</h2>
            <p class="text-gray-500">Découvrez les dernières opportunités de la communauté.</p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-10 max-w-3xl mx-auto">
            <div class="flex flex-col md:flex-row items-stretch gap-3">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input.debounce.500ms="fetchJobs()"
                        class="w-full py-2.5 pl-10 pr-4 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm placeholder-gray-400"
                        placeholder="Rechercher un poste, une entreprise...">
                </div>

                <!-- Skill Filter -->
                <div class="w-full md:w-48">
                    <select x-model="skill" @change="fetchJobs()"
                        class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 text-sm cursor-pointer">
                        <option value="">Toutes les compétences</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Button -->
                <button @click="fetchJobs()"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium text-sm hover:bg-blue-700 transition-colors">
                    Rechercher
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>

        <!-- Grid Container -->
        <div x-show="!loading">
            <!-- AJAX Results -->
            <template x-if="useAjax">
                <div>
                    <!-- Empty State -->
                    <div x-show="jobs.length === 0" class="col-span-full py-16 text-center">
                        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucune offre trouvée</h3>
                        <p class="text-gray-500 text-sm">Essayez de modifier vos filtres ou revenez plus tard.</p>
                    </div>

                    <!-- Jobs Grid from AJAX -->
                    <div x-show="jobs.length > 0" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="job in jobs" :key="job.id">
                            <div
                                class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md hover:border-gray-300 transition-all duration-200">
                                <!-- Image Section -->
                                <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                                    <template x-if="job.image">
                                        <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            :src="'/storage/' + job.image" :alt="job.title">
                                    </template>
                                    <template x-if="!job.image">
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- Content Section -->
                                <div class="p-5 flex flex-col flex-grow">
                                    <!-- Company Badge -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold"
                                            x-text="job.company.charAt(0)"></div>
                                        <span class="text-sm text-gray-500 font-medium" x-text="job.company"></span>
                                    </div>

                                    <!-- Title -->
                                    <h3
                                        class="font-semibold text-gray-900 text-lg leading-tight mb-2 group-hover:text-blue-600 transition-colors">
                                        <a :href="job.url" x-text="job.title"></a>
                                    </h3>

                                    <!-- Description -->
                                    <p class="text-sm text-gray-500 line-clamp-2 mb-4" x-text="job.description"></p>

                                    <!-- Skills Tags -->
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        <template x-for="skill in job.skills.slice(0, 3)" :key="skill.id">
                                            <span
                                                class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full"
                                                x-text="skill.name"></span>
                                        </template>
                                        <template x-if="job.skills.length > 3">
                                            <span
                                                class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full"
                                                x-text="'+' + (job.skills.length - 3)"></span>
                                        </template>
                                    </div>

                                    <!-- Footer -->
                                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-end">
                                        <a :href="job.url"
                                            class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                            Voir l'offre
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Server-side rendered content (initial load) -->
            <template x-if="!useAjax">
                <div>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @include('emplois._job_card')
                    </div>

                    <!-- Pagination -->
                    <div class="mt-10 flex justify-center">
                        {{ $emplois->withQueryString()->links() }}
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection

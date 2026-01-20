@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="-mt-8 mb-10 bg-white border-b border-gray-200">
    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="max-w-2xl text-center mx-auto">
            <h1 class="block font-bold text-gray-800 text-4xl md:text-5xl lg:text-6xl">
                Trouvez votre futur job
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Explorez notre collection d'opportunités professionnelles et donnez un nouvel élan à votre carrière.
            </p>
        </div>

        <!-- Search/Filter Bar -->
        <div class="mt-10 max-w-3xl mx-auto">
            <div class="bg-white p-2 rounded-2xl shadow-lg border border-gray-100 flex flex-col sm:flex-row gap-2">
                <input type="text" id="search-input" placeholder="Titre, entreprise, mots-clés..." 
                    class="flex-1 px-4 py-3 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none text-gray-700"
                    value="{{ request('search') }}">
                
                <select id="skill-filter" class="px-4 py-3 rounded-xl border-none bg-gray-50 focus:ring-2 focus:ring-blue-500 outline-none text-gray-700 min-w-[180px]">
                    <option value="">Toutes les compétences</option>
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                            {{ $skill->name }}
                        </option>
                    @endforeach
                </select>

                <button type="button" id="reset-filters" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl mt-2 sm:mt-0 transition-all">
                    Rechercher
                </button>
            </div>
        </div>
    </div>
</div>

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div id="jobsContainer">
        <div class="mb-8 sm:mb-12 text-center">
            <h2 class="text-2xl font-bold md:text-3xl text-gray-800">
                Offres d'emploi à la une
            </h2>
            <p class="text-gray-500 mt-2" id="results-count">
                {{ $emplois->total() }} offre{{ $emplois->total() > 1 ? 's' : '' }} trouvée{{ $emplois->total() > 1 ? 's' : '' }}
            </p>
        </div>

        <!-- Job List Area -->
        <div id="jobs-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($emplois as $emploi)
                <a href="{{ route('emplois.show', $emploi) }}" class="group flex flex-col bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all overflow-hidden h-full">
                    <div class="aspect-video relative overflow-hidden bg-slate-50 border-b border-gray-100">
                        @if($emploi->image)
                            <img src="{{ asset('storage/'.$emploi->image) }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="{{ $emploi->company }}">
                        @else
                            <div class="flex items-center justify-center h-full bg-slate-50">
                                <svg class="size-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3 text-right">
                            @foreach($emploi->skills->take(1) as $skill)
                                <span class="py-1 px-3 bg-white/90 backdrop-blur shadow-sm rounded-full text-[10px] font-bold text-blue-600 uppercase tracking-widest">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                            {{ $emploi->title }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                            {{ $emploi->description }}
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-x-2">
                                <div class="size-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-600 line-clamp-1">{{ $emploi->company }}</span>
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
            @endforeach
        </div>

        @if($emplois->hasPages())
        <div class="mt-12 flex justify-center" id="pagination-container">
            {{ $emplois->links() }}
        </div>
        @endif

        <!-- Empty State -->
        <div class="hidden" id="empty-state">
            <div class="text-center py-16">
                <div class="size-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-500">Essayez de modifier vos filtres.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .hidden { display: none !important; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .size-3 { width: 0.75rem; height: 0.75rem; }
    .size-4 { width: 1rem; height: 1rem; }
    .size-6 { width: 1.5rem; height: 1.5rem; }
    .size-8 { width: 2rem; height: 2rem; }
    .size-10 { width: 2.5rem; height: 2.5rem; }
    .size-16 { width: 4rem; height: 4rem; }
</style>
@endsection

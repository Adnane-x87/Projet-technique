@extends('layouts.app')

@section('content')
    <!-- Search Section -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 2rem; margin-bottom: 10px;">Offres d'emploi</h1>
        <p style="color: #666; margin-bottom: 20px;">Trouvez votre prochaine opportunité</p>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="text" id="search-input" placeholder="Rechercher..." style="max-width: 300px;"
                value="{{ request('search') }}">

            <select id="skill-filter" style="max-width: 200px;">
                <option value="">Toutes les compétences</option>
                @foreach ($skills as $skill)
                    <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                        {{ $skill->name }}
                    </option>
                @endforeach
            </select>

            <button type="button" id="reset-filters" class="btn">Réinitialiser</button>
        </div>
    </div>

    <!-- Results Count -->
    <p style="color: #666; margin-bottom: 20px;">
        <span id="results-count">{{ $emplois->count() }} offre{{ $emplois->count() > 1 ? 's' : '' }}</span> trouvée(s)
    </p>

    <!-- Job List -->
    <div id="jobs-grid">
        @foreach ($emplois as $emploi)
            <div class="card">
                <div style="display: flex; gap: 20px; align-items: start; margin-bottom: 15px;">
                    @if ($emploi->image)
                        <img src="{{ asset('storage/' . $emploi->image) }}" alt="{{ $emploi->company }}"
                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                    @endif
                    <div>
                        <h2 style="font-size: 1.5rem; margin-bottom: 5px;">{{ $emploi->title }}</h2>
                        <p style="color: #666; font-size: 1.1rem;">{{ $emploi->company }}</p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; color: #444; line-height: 1.6; white-space: pre-line;">
                    {{ $emploi->description }}
                </div>

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @foreach ($emploi->skills as $skill)
                        <span
                            style="background: #e0e0e0; padding: 5px 12px; border-radius: 4px; font-size: 14px;">{{ $skill->name }}</span>
                    @endforeach
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <a href="{{ route('emplois.show', $emploi) }}" class="btn btn-primary">Voir l'offre</a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div id="pagination-container" style="margin-top: 30px; display: flex; justify-content: center; gap: 5px;">
        @if ($emplois->onFirstPage())
            <span class="btn" style="opacity: 0.5; cursor: not-allowed;">« Précédent</span>
        @else
            <a href="{{ $emplois->previousPageUrl() }}" class="btn">« Précédent</a>
        @endif

        @foreach ($emplois->getUrlRange(1, $emplois->lastPage()) as $page => $url)
            @if ($page == $emplois->currentPage())
                <span class="btn btn-primary">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="btn">{{ $page }}</a>
            @endif
        @endforeach

        @if ($emplois->hasMorePages())
            <a href="{{ $emplois->nextPageUrl() }}" class="btn">Suivant »</a>
        @else
            <span class="btn" style="opacity: 0.5; cursor: not-allowed;">Suivant »</span>
        @endif
    </div>

    <!-- Empty State -->
    <div class="hidden" id="empty-state" style="text-align: center; padding: 40px;">
        <h3>Aucun résultat trouvé</h3>
        <p style="color: #666;">Essayez de modifier vos filtres.</p>
        <button onclick="resetFilters()" class="btn" style="margin-top: 15px;">Réinitialiser</button>
    </div>


    <style>
        .hidden {
            display: none !important;
        }
    </style>
@endsection

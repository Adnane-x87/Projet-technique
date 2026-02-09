@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

            <!-- Image Header -->
            @if ($emploi->image)
                <div class="aspect-[3/1] w-full overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $emploi->image) }}" alt="{{ $emploi->company }}"
                        class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6 md:p-8">
                <!-- Company Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr($emploi->company, 0, 1) }}
                    </div>
                    <span class="text-sm text-gray-500 font-medium">{{ $emploi->company }}</span>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $emploi->title }}</h1>



                <!-- Skills -->
                @if ($emploi->skills->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Compétences requises</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($emploi->skills as $skill)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div class="pt-6 border-t border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Description du poste</h3>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $emploi->description }}</p>
                </div>

                <!-- Back Link -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('emplois.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour aux offres
                    </a>
                </div>
            </div>
                <!-- Actions -->
                @canany(['update-job', 'delete-job'], $emploi)
                    <div class="pt-6 border-t border-gray-100 flex gap-3 px-6 md:px-8 pb-6 md:pb-8">
                        @can('update-job', $emploi)
                            <a href="{{ route('emplois.edit', $emploi) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </a>
                        @endcan

                        @can('delete-job', $emploi)
                            <form action="{{ route('emplois.destroy', $emploi) }}" method="POST"
                                onsubmit="return confirm('Confirmer la suppression ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        @endcan
                    </div>
                @endcanany
            </div>
        </div>
    </div>
@endsection

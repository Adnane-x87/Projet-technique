@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 dark:bg-zinc-950 min-h-screen">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tableau de Bord</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Gérez vos offres d'emploi et suivez vos performances.</p>
            </div>
            <button onclick="openCreateModal()" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter une offre
            </button>
        </div>

        <!-- Search & Filter Area -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8 dark:bg-zinc-900 dark:border-zinc-800">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" id="search-input" 
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 transition-colors dark:bg-zinc-950 dark:border-zinc-800 dark:text-white"
                        placeholder="Rechercher par titre, entreprise...">
                </div>

                <div class="md:w-64">
                    <select id="skill-filter" 
                        class="block w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm transition-colors cursor-pointer dark:bg-zinc-950 dark:border-zinc-800 dark:text-white">
                        <option value="">Toutes les compétences</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="resetFilters()" 
                    class="px-6 py-3 bg-gray-50 text-gray-700 font-medium rounded-lg hover:bg-gray-100 border border-gray-200 transition-all active:scale-95 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700 dark:hover:bg-zinc-700">
                    Effacer
                </button>
            </div>
        </div>

        <!-- Job List Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 dark:bg-zinc-900 dark:border-zinc-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Offres d'emploi</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $emplois->total() }} au total
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-zinc-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Titre / Mission</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Entreprise</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Compétences</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Publiée le</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jobs-table-body" class="bg-white divide-y divide-gray-100 dark:bg-zinc-900 dark:divide-zinc-800">
                        @forelse($emplois as $emploi)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $emploi->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($emploi->image)
                                            <img src="{{ asset('storage/' . $emploi->image) }}" class="h-8 w-8 rounded-md object-cover mr-3 border border-gray-100 shadow-sm">
                                        @else
                                            <div class="h-8 w-8 rounded-md bg-gray-100 flex items-center justify-center mr-3 border border-gray-100">
                                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                                            </div>
                                        @endif
                                        <div class="text-sm text-gray-600 dark:text-zinc-400">{{ $emploi->company }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($emploi->skills->take(2) as $skill)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-zinc-800 dark:text-zinc-300">
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                        @if ($emploi->skills->count() > 2)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-600">
                                                +{{ $emploi->skills->count() - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-500 dark:text-zinc-400">{{ $emploi->created_at->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button onclick='openEditModal({{ $emploi->id }}, {{ json_encode($emploi->title) }}, {{ json_encode($emploi->company) }}, {{ json_encode($emploi->description) }}, {{ json_encode($emploi->skills->pluck('id')) }})'
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('emplois.destroy', $emploi) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Confirmer la suppression ?')"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-lg font-medium">Aucune offre trouvée</p>
                                        <p class="text-sm">Commencez par ajouter une nouvelle mission.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            <div id="pagination-container" class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-center dark:bg-zinc-900/50 dark:border-zinc-800">
                <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    @if ($emplois->onFirstPage())
                        <span class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-600">
                            Précédent
                        </span>
                    @else
                        <a href="{{ $emplois->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                            Précédent
                        </a>
                    @endif

                    @foreach ($emplois->getUrlRange(1, $emplois->lastPage()) as $page => $url)
                        @if ($page == $emplois->currentPage())
                            <span class="z-10 bg-blue-600 border-blue-600 text-white relative inline-flex items-center px-4 py-2 border text-sm font-bold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($emplois->hasMorePages())
                        <a href="{{ $emplois->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                            Suivant
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-600">
                            Suivant
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <div id="jobModal" class="fixed inset-0 bg-zinc-950/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 transition-all duration-300 opacity-0 pointer-events-none">
        <!-- Modal Content -->
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95 dark:bg-zinc-900 dark:border-zinc-800">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white dark:bg-zinc-900 dark:border-zinc-800">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-white">Ajouter une offre</h2>
                <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <div class="p-6 overflow-y-auto">
                <form id="jobForm" onsubmit="handleFormSubmit(event)" class="space-y-5" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1">Titre du poste</label>
                        <input type="text" id="title" name="title" required
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm dark:bg-zinc-950 dark:border-zinc-800 dark:text-white"
                            placeholder="ex: Développeur PHP Fullstack">
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1">Entreprise</label>
                        <input type="text" id="company" name="company" required
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm dark:bg-zinc-950 dark:border-zinc-800 dark:text-white"
                            placeholder="Nom de la société">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4" required
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm resize-none dark:bg-zinc-950 dark:border-zinc-800 dark:text-white"
                            placeholder="Détaillez la mission et les pré-requis..."></textarea>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1">Logo ou Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50 dark:bg-zinc-950 dark:border-zinc-800">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-zinc-400">
                                    <label for="image" class="relative cursor-pointer bg-white dark:bg-zinc-900 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Télécharger un fichier</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-zinc-500">PNG, JPG up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3">Compétences requises</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($skills as $skill)
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 dark:border-zinc-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-all select-none group">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" id="skill_{{ $skill->id }}"
                                        class="h-4 w-4 text-blue-600 border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-600 dark:text-zinc-400 group-hover:text-gray-900 dark:group-hover:text-white">{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sticky Footer in Modal -->
                    <div class="flex gap-3 pt-6 border-t border-gray-100 bg-white sticky bottom-0 dark:bg-zinc-900 dark:border-zinc-800">
                        <button type="button" onclick="closeModal()" 
                            class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700">
                            Annuler
                        </button>
                        <button type="submit" id="submitBtn"
                            class="flex-1 px-4 py-2.5 bg-blue-600 border border-transparent text-sm font-semibold text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-all">
                            Enregistrer l'offre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.emploisStoreRoute = '{{ route('emplois.store') }}';
    </script>
@endsection

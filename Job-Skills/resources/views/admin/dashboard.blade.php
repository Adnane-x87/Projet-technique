<x-layouts.app>
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-2">Tableau de bord</h1>
                <p class="text-slate-500 font-medium">Gestion centralisée des opportunités et des talents.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <button type="button" onclick="openCreateModal()" class="btn-modern btn-modern-primary group">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter une offre
                </button>
            </div>
        </div>

        <!-- Stats Grid (Simple) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Offres</p>
                <p class="text-3xl font-extrabold text-slate-900">{{ $emplois->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Compétences</p>
                <p class="text-3xl font-extrabold text-slate-900">{{ $skills->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Vues Totales</p>
                <p class="text-3xl font-extrabold text-slate-900">1.2k</p>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Offre & Entreprise</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Compétences</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($emplois as $emploi)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden">
                                        @if($emploi->image)
                                            <img src="{{ $emploi->image }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-lg font-bold text-slate-300">{{ substr($emploi->company, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $emploi->title }}</p>
                                        <p class="text-xs text-slate-500 font-medium">{{ $emploi->company }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($emploi->skills->take(2) as $skill)
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-md border border-slate-200/50">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                    @if($emploi->skills->count() > 2)
                                        <span class="px-2 py-1 text-[10px] font-bold text-slate-400">+{{ $emploi->skills->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-500">{{ $emploi->created_at->format('d M, Y') }}</span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" 
                                        onclick="openEditModal({{ $emploi->id }}, '{{ addslashes($emploi->title) }}', '{{ addslashes($emploi->company) }}', '{{ addslashes($emploi->description) }}', '{{ $emploi->image }}', {{ json_encode($emploi->skills->pluck('id')) }})"
                                        class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('emplois.destroy', $emploi) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" onclick="return confirm('Êtes-vous sûr ?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <p class="text-slate-400 font-medium">Aucune offre d'emploi n'a été trouvée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <x-job-modal :skills="$skills" />
</x-layouts.app>

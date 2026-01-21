@forelse($emplois as $emploi)
    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                @if ($emploi->image)
                    <img src="{{ asset('storage/' . $emploi->image) }}" class="h-10 w-10 rounded-lg object-cover mr-3 border border-gray-100 dark:border-gray-700 shadow-sm">
                @else
                    <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3 border border-gray-100 dark:border-gray-700">
                        <i data-lucide="briefcase" class="h-5 w-5 text-gray-400"></i>
                    </div>
                @endif
                <div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $emploi->title }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $emploi->company }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="flex flex-wrap gap-1.5">
                @foreach ($emploi->skills->take(2) as $skill)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        {{ $skill->name }}
                    </span>
                @endforeach
                @if ($emploi->skills->count() > 2)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400">
                        +{{ $emploi->skills->count() - 2 }}
                    </span>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $emploi->created_at->diffForHumans() }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('emplois.show', $emploi) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-semibold inline-flex items-center gap-1">
                Voir
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
            <div class="flex flex-col items-center">
                <i data-lucide="folder-open" class="h-10 w-10 text-gray-300 mb-3 dark:text-gray-600"></i>
                <p class="text-base font-medium">Aucune offre trouvée</p>
                <p class="text-sm mt-1">Essayez de modifier vos filtres.</p>
            </div>
        </td>
    </tr>
@endforelse

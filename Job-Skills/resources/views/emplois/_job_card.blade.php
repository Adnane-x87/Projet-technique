@forelse($emplois as $emploi)
    <div
        class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md hover:border-gray-300 transition-all duration-200">

        <!-- Image Section -->
        <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
            @php
                $imagePath = $emploi->image;
                $isUrl = str_starts_with($imagePath, 'http');
                $fileExists =
                    $isUrl || ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath));
            @endphp

            @if ($fileExists)
                <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    src="{{ $isUrl ? $imagePath : asset('storage/' . $imagePath) }}" alt="{{ $emploi->title }}"
                    onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100\'><svg class=\'w-12 h-12 text-gray-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/></svg></div>'">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            @endif
        </div>

        <!-- Content Section -->
        <div class="p-5 flex flex-col flex-grow">
            <!-- Company Badge -->
            <div class="flex items-center gap-2 mb-3">
                <div
                    class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold">
                    {{ substr($emploi->company, 0, 1) }}
                </div>
                <span class="text-sm text-gray-500 font-medium">{{ $emploi->company }}</span>
            </div>

            <!-- Title -->
            <h3
                class="font-semibold text-gray-900 text-lg leading-tight mb-2 group-hover:text-blue-600 transition-colors">
                <a href="{{ route('emplois.show', $emploi) }}">{{ $emploi->title }}</a>
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                {{ $emploi->description }}
            </p>

            <!-- Skills Tags -->
            <div class="flex flex-wrap gap-1.5 mb-4">
                @foreach ($emploi->skills->take(3) as $skill)
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                        {{ $skill->name }}
                    </span>
                @endforeach
                @if ($emploi->skills->count() > 3)
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                        +{{ $emploi->skills->count() - 3 }}
                    </span>
                @endif
            </div>

            <!-- Footer -->
            <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex gap-2">
                    @can('update-job', $emploi)
                        <a href="{{ route('emplois.edit', $emploi) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Modifier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    @endcan
                    @can('delete-job', $emploi)
                        <form action="{{ route('emplois.destroy', $emploi) }}" method="POST" onsubmit="return confirm('Confirmer ?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endcan
                </div>

                <a href="{{ route('emplois.show', $emploi) }}"
                    class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    Voir l'offre
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full py-16 text-center">
        <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Aucune offre trouvée</h3>
        <p class="text-gray-500 text-sm">Essayez de modifier vos filtres ou revenez plus tard.</p>
    </div>
@endforelse

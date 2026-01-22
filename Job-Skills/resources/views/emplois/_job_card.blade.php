@forelse($emplois as $emploi)
    <div class="group flex flex-col h-full bg-white border border-gray-200 rounded-2xl p-4 transition-all hover:shadow-lg dark:bg-zinc-900 dark:border-zinc-800">
        
        <!-- Image Section -->
        <div class="relative w-full aspect-[4/3] overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 dark:bg-gradient-to-br dark:from-zinc-950 dark:to-zinc-900 flex-shrink-0">
            @php
                $imagePath = $emploi->image;
                $isUrl = str_starts_with($imagePath, 'http');
                $fileExists = $isUrl || ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath));
            @endphp

            @if($fileExists)
                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                     src="{{ $isUrl ? $imagePath : asset('storage/' . $imagePath) }}" 
                     alt="{{ $emploi->title }}"
                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-gray-100 to-gray-200 dark:bg-zinc-950\'><div class=\'w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-3 dark:bg-blue-900/20\'><i data-lucide=\'briefcase\' class=\'w-8 h-8 text-blue-600 dark:text-blue-400\'></i></div><span class=\'text-xs font-bold text-gray-700 dark:text-zinc-400 uppercase tracking-widest\'>Logo non disponible</span></div>'">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-gray-100 to-gray-200 dark:bg-zinc-950">
                    <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-3 dark:bg-blue-900/20">
                        <i data-lucide="briefcase" class="w-8 h-8 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-700 dark:text-zinc-400 uppercase tracking-widest">Logo non disponible</span>
                </div>
            @endif

            <!-- Company Overlay -->
            <div class="absolute bottom-3 left-3">
                <div class="bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg border border-white/30 shadow-md flex items-center gap-2 dark:bg-zinc-900/95 dark:border-zinc-800">
                    <div class="w-5 h-5 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[8px]">
                        {{ substr($emploi->company, 0, 1) }}
                    </div>
                    <span class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-wider">{{ $emploi->company }}</span>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="pt-5 flex-grow flex flex-col">
            <!-- Categories/Skills -->
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($emploi->skills->take(3) as $skill)
                    <span class="text-[10px] font-bold uppercase tracking-widest text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $skill->name }}
                    </span>
                @endforeach
            </div>

            <!-- Title -->
            <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition-colors leading-tight mb-2 dark:text-white">
               <a href="{{ route('emplois.show', $emploi) }}">{{ $emploi->title }}</a>
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-700 mb-6 line-clamp-2 dark:text-zinc-400">
                {{ $emploi->description }}
            </p>

            <!-- Metadata - Pushed to Bottom of Content Div -->
            <div class="mt-auto pt-3 flex items-center justify-between text-[11px] font-medium text-gray-600 dark:text-zinc-500">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>{{ $emploi->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    <span>Casablanca</span>
                </div>
            </div>

            <!-- Action Button - Always at the bottom -->
            <div class="mt-5">
                <a href="{{ route('emplois.show', $emploi) }}" 
                   class="w-full bg-blue-600 text-white text-[11px] font-bold uppercase tracking-widest py-3.5 px-4 rounded-xl hover:bg-blue-700 transition-all shadow-md active:scale-95 text-center flex items-center justify-center gap-2 group/btn">
                    Voir les détails
                    <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover/btn:translate-x-1"></i>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full py-20 text-center">
        <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 dark:bg-zinc-900">
            <i data-lucide="search-x" class="w-10 h-10 text-gray-300"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aucune offre trouvée</h3>
        <p class="text-gray-500 dark:text-zinc-400">Essayez de modifier vos filtres ou revenez plus tard.</p>
    </div>
@endforelse

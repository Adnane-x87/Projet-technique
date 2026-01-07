<x-layouts.app>
    <!-- Hero Section -->
    <div class="relative pt-16 pb-20 lg:pt-24 lg:pb-28">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 mb-6">
                Découvrez votre <span class="text-gradient">prochain défi</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                Rejoignez les meilleures équipes au monde. Parcourez des milliers d'offres sélectionnées avec soin pour propulser votre carrière.
            </p>

            <!-- Search & Filter Card -->
            <div class="max-w-4xl mx-auto bg-white/60 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-blue-900/5 border border-white p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="search-input" 
                            placeholder="Titre du poste, entreprise ou mots-clés" 
                            class="input-modern !pl-12 !py-4 font-medium"
                            value="{{ request('search') }}">
                    </div>
                    
                    <!-- Skill Filter Dropdown -->
                    <div class="md:w-64">
                        <select id="skill-filter" class="form-select input-modern !py-4 font-medium appearance-none">
                            <option value="">Toutes les compétences</option>
                            @foreach($skills as $skill)
                                <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Submit Button Placeholder (Reset actually) -->
                    <button type="button" id="reset-filters" class="btn-modern btn-modern-secondary !py-4">
                        Réinitialiser
                    </button>
                </div>
                
                <!-- Quick Suggestion chips -->
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2">Populaire :</span>
                    @foreach($skills->take(5) as $skill)
                        <button type="button" 
                            onclick="setSkillFilter({{ $skill->id }})"
                            class="px-4 py-1.5 rounded-full bg-slate-50 border border-slate-100 text-slate-500 text-sm font-medium hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 transition-all">
                            {{ $skill->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 pb-32">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
                Opportunités récentes
                <span id="results-count" class="text-sm font-semibold bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-100">
                    {{ $emplois->count() }} offre{{ $emplois->count() > 1 ? 's' : '' }}
                </span>
            </h2>
            <div class="flex items-center gap-3 text-sm font-semibold text-slate-400">
                Trier par :
                <select class="bg-transparent border-none focus:ring-0 text-slate-900 cursor-pointer">
                    <option>Plus récents</option>
                    <option>Salaire élevé</option>
                </select>
            </div>
        </div>

        <!-- Job Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="jobs-grid">
            @foreach($emplois as $emploi)
            <div class="group bg-white rounded-[1.5rem] border border-slate-100 hover:border-blue-200 p-6 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-900/5 flex flex-col h-full relative overflow-hidden">
                <!-- Hover Decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-sky-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <!-- Header Card -->
                <div class="flex items-start justify-between mb-6 relative">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden shadow-sm group-hover:scale-110 transition-transform duration-300">
                        @if($emploi->image)
                            <img src="{{ $emploi->image }}" alt="{{ $emploi->company }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-slate-300">{{ strtoupper(substr($emploi->company, 0, 1)) }}</span>
                        @endif
                    </div>
                    <button class="text-slate-300 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 relative">
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-700 transition-colors leading-tight">
                        {{ $emploi->title }}
                    </h3>
                    <div class="flex items-center gap-2 mb-6">
                        <p class="text-slate-500 font-medium text-sm">{{ $emploi->company }}</p>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <p class="text-slate-400 text-xs uppercase tracking-wider font-bold">Temps plein</p>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-8">
                        @foreach($emploi->skills->take(3) as $skill)
                            <span class="px-3 py-1 bg-blue-50/50 text-blue-700 text-[11px] font-bold uppercase tracking-wider rounded-lg border border-blue-100/50">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                        @if($emploi->skills->count() > 3)
                            <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[11px] font-bold rounded-lg border border-slate-100">
                                +{{ $emploi->skills->count() - 3 }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="pt-6 border-t border-slate-50 flex items-center justify-between relative">
                    <div>
                        <p class="text-slate-900 font-bold">45k - 65k €</p>
                        <p class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">Annuel</p>
                    </div>
                    <a href="{{ route('emplois.show', $emploi) }}" class="btn-modern btn-modern-primary !px-6 !py-2.5 !text-sm">
                        Voir l'offre
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div class="hidden text-center py-32" id="empty-state">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 21h4m1.157-1.53c1.052-.147 1.967-.74 2.556-1.588.59-.848.887-1.895.887-2.982 0-3.314-2.686-6-6-6s-6 2.686-6 6c0 1.087.297 2.134.887 2.982.589.848 1.504 1.441 2.556 1.588m3.111-5.47a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Aucun résultat trouvé</h3>
            <p class="text-slate-500 mb-10">Essayez de modifier vos filtres ou mot-clé pour explorer plus d'opportunités.</p>
            <button onclick="resetFilters()" class="btn-modern btn-modern-primary">Parcourir tout</button>
        </div>

        <!-- Loading Overlay -->
        <div class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/10 backdrop-blur-sm" id="loading-state">
            <div class="bg-white p-8 rounded-[2rem] shadow-2xl flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-blue-100 border-t-blue-700 rounded-full animate-spin"></div>
                <p class="text-slate-900 font-bold mt-4 tracking-tight">Recherche en cours...</p>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const skillFilter = document.getElementById('skill-filter');
        const jobsGrid = document.getElementById('jobs-grid');
        const resultsCount = document.getElementById('results-count');
        const emptyState = document.getElementById('empty-state');
        const loadingState = document.getElementById('loading-state');
        const resetBtn = document.getElementById('reset-filters');
        
        let debounceTimer;
        
        function setSkillFilter(id) {
            skillFilter.value = id;
            fetchJobs();
        }

        function resetFilters() {
            searchInput.value = '';
            skillFilter.value = '';
            fetchJobs();
        }

        function fetchJobs() {
            const search = searchInput.value;
            const skill = skillFilter.value;
            
            loadingState.classList.remove('hidden');
            jobsGrid.classList.add('opacity-30');
            
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (skill) params.append('skill', skill);
            
            fetch('/api/emplois?' + params.toString())
                .then(res => res.json())
                .then(data => {
                    loadingState.classList.add('hidden');
                    jobsGrid.classList.remove('opacity-30');
                    
                    resultsCount.textContent = data.count + ' offre' + (data.count > 1 ? 's' : '');
                    
                    if (data.count === 0) {
                        jobsGrid.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                    } else {
                        jobsGrid.classList.remove('hidden');
                        emptyState.classList.add('hidden');
                        renderJobs(data.emplois);
                    }
                })
                .catch(err => {
                    loadingState.classList.add('hidden');
                    jobsGrid.classList.remove('opacity-30');
                    console.error(err);
                });
        }
        
        function renderJobs(emplois) {
            jobsGrid.innerHTML = emplois.map(job => `
                <div class="group bg-white rounded-[1.5rem] border border-slate-100 hover:border-blue-200 p-6 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-900/5 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-sky-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex items-start justify-between mb-6 relative">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden shadow-sm group-hover:scale-110 transition-transform duration-300">
                            ${job.image 
                                ? `<img src="${job.image}" alt="${job.company}" class="w-full h-full object-cover">`
                                : `<span class="text-2xl font-bold text-slate-300">${job.company.substring(0,1).toUpperCase()}</span>`
                            }
                        </div>
                        <button class="text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 relative">
                        <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-700 transition-colors leading-tight">
                            ${job.title}
                        </h3>
                        <div class="flex items-center gap-2 mb-6">
                            <p class="text-slate-500 font-medium text-sm">${job.company}</p>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <p class="text-slate-400 text-xs uppercase tracking-wider font-bold">Temps plein</p>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-8">
                            ${job.skills.slice(0,3).map(s => `
                                <span class="px-3 py-1 bg-blue-50/50 text-blue-700 text-[11px] font-bold uppercase tracking-wider rounded-lg border border-blue-100/50">
                                    ${s.name}
                                </span>
                            `).join('')}
                            ${job.skills.length > 3 ? `
                                <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[11px] font-bold rounded-lg border border-slate-100">
                                    +${job.skills.length - 3}
                                </span>
                            ` : ''}
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between relative">
                        <div>
                            <p class="text-slate-900 font-bold">45k - 65k €</p>
                            <p class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">Annuel</p>
                        </div>
                        <a href="${job.url}" class="btn-modern btn-modern-primary !px-6 !py-2.5 !text-sm">
                            Voir l'offre
                        </a>
                    </div>
                </div>
            `).join('');
        }
        
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchJobs, 300);
        });
        
        skillFilter.addEventListener('change', fetchJobs);
        resetBtn.addEventListener('click', resetFilters);
    </script>
</x-layouts.app>

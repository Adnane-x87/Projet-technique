<x-layouts.app>
    <div class="max-w-5xl mx-auto px-6 py-16 lg:py-24">
        <!-- Breadcrumb -->
        <nav class="mb-12">
            <a href="{{ route('emplois.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-700 transition-colors uppercase tracking-widest">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux offres
            </a>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-12">
                <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden shadow-sm">
                                @if($emploi->image)
                                    <img src="{{ $emploi->image }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl font-bold text-slate-300">{{ substr($emploi->company, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">{{ $emploi->title }}</h1>
                                <p class="text-lg text-slate-500 font-medium">{{ $emploi->company }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                             <span class="px-3 py-1 bg-green-50 text-green-700 text-[11px] font-bold uppercase tracking-wider rounded-lg border border-green-100">
                                Nouveau
                            </span>
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[11px] font-bold uppercase tracking-wider rounded-lg border border-blue-100">
                                Temps plein
                            </span>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">À propos du poste</h2>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line text-lg">
                            {{ $emploi->description }}
                        </p>
                    </div>

                    <div class="mt-12 pt-12 border-t border-slate-50">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 font- jakarta">Compétences recherchées</h2>
                        <div class="flex flex-wrap gap-3">
                            @foreach($emploi->skills as $skill)
                                <div class="px-5 py-2.5 bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition-colors hover:border-blue-200 hover:bg-blue-50/30">
                                    {{ $skill->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-32 space-y-6">
                    <!-- Apply Card -->
                    <div class="bg-blue-700 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-blue-900/20">
                        <h3 class="text-xl font-bold mb-2 tracking-tight">Postuler maintenant</h3>
                        <p class="text-blue-100/80 text-sm mb-8 leading-relaxed">Envoyez votre candidature directement et obtenez une réponse sous 48h.</p>
                        
                        <a href="mailto:contact@{{ Str::slug($emploi->company) }}.com" class="btn-modern !bg-white !text-blue-700 w-full mb-4 !py-4 shadow-xl">
                            Envoyer mon CV
                        </a>
                        <p class="text-center text-[10px] text-blue-200 uppercase tracking-widest font-bold">Sans engagement</p>
                    </div>

                    <!-- Job Summary -->
                    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
                        <h4 class="font-bold text-slate-900 mb-6 text-sm uppercase tracking-widest">Informations</h4>
                        <ul class="space-y-6">
                            <li class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter">Salaire</p>
                                    <p class="text-sm font-bold text-slate-700">45k - 65k €</p>
                                </div>
                            </li>
                            <li class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter">Localisation</p>
                                    <p class="text-sm font-bold text-slate-700">Hybride / Paris</p>
                                </div>
                            </li>
                             <li class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter">Publié il y a</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $emploi->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

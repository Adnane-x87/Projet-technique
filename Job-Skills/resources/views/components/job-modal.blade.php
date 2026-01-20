@props(['skills'])

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-modal {
        animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
</style>

<!-- Backdrop -->
<div id="job-modal-backdrop" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 transition-opacity duration-300" onclick="closeJobModal()"></div>

<!-- Modal -->
<div id="job-modal" class="hidden fixed inset-0 z-[60] overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-6">
        <div class="animate-modal relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl border border-white overflow-hidden" onclick="event.stopPropagation()">
            
            <!-- Header -->
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 id="modal-title" class="text-2xl font-extrabold text-slate-900 tracking-tight">Ajouter un emploi</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Remplissez les détails de l'opportunité.</p>
                </div>
                <button type="button" onclick="closeJobModal()" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:border-slate-300 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Body -->
            <form id="job-form" method="POST" class="p-10">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="col-span-full">
                        <label class="form-label">Titre du poste</label>
                        <input type="text" id="modal-job-title" name="title" required
                            class="input-modern"
                            placeholder="Ex: Product Designer Senior">
                    </div>

                    <!-- Company -->
                    <div>
                        <label class="form-label">Entreprise</label>
                        <input type="text" id="modal-job-company" name="company" required
                            class="input-modern"
                            placeholder="Ex: Apple">
                    </div>

                    <!-- Image URL -->
                    <div>
                        <label class="form-label">Logo URL</label>
                        <input type="url" id="modal-job-image" name="image"
                            class="input-modern"
                            placeholder="https://...">
                    </div>

                    <!-- Description -->
                    <div class="col-span-full">
                        <label class="form-label">Description</label>
                        <textarea id="modal-job-description" name="description" rows="4" required
                            class="input-modern resize-none"
                            placeholder="Décrivez les responsabilités et les attentes du poste..."></textarea>
                    </div>

                    <!-- Skills -->
                    <div class="col-span-full">
                        <label class="form-label mb-3">Compétences requises</label>
                        <div id="modal-skills-container" class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 max-h-48 overflow-y-auto">
                            @foreach($skills as $skill)
                            <label class="flex items-center gap-3 text-sm font-semibold text-slate-600 cursor-pointer p-2 rounded-xl hover:bg-white border border-transparent hover:border-slate-200 transition-all group">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="w-5 h-5 rounded-lg border-slate-300 text-blue-700 focus:ring-blue-700/20 transition-all">
                                <span class="group-hover:text-slate-900">{{ $skill->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-10 pt-8 border-t border-slate-50 flex items-center justify-end gap-4">
                    <button type="button" onclick="closeJobModal()" class="btn-modern btn-modern-secondary">
                        Annuler
                    </button>
                    <button type="submit" class="btn-modern btn-modern-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.emploisStoreRoute = '{{ route("emplois.store") }}';
</script>

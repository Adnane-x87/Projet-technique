@extends('layouts.admin')

@section('content')
    <div x-data='emploiManager(@json($initialJobs), {{ $emplois->total() }})' x-init="init()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord</h1>
                <p class="mt-1 text-sm text-gray-500">Gérez vos offres d'emploi et suivez vos performances.</p>
            </div>
            <div class="flex items-center gap-3">
                @can('manage-jobs')
                    <button @click="openCreateModal()"
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter une offre
                    </button>
                @endcan
                @if (Auth::user()->is_admin)
                    <span
                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Admin
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        User
                    </span>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Search & Filter Area -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 mb-6">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" x-model="search" @input.debounce.300ms="fetchJobs"
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 bg-gray-50"
                        placeholder="Rechercher par titre, entreprise...">
                </div>

                <div class="md:w-56">
                    <select x-model="skill" @change="fetchJobs"
                        class="block w-full py-2.5 px-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm cursor-pointer bg-gray-50">
                        <option value="">Toutes les compétences</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" @click="resetFilters"
                    class="px-4 py-2.5 bg-gray-100 text-gray-600 font-medium text-sm rounded-lg hover:bg-gray-200 transition-colors">
                    Effacer
                </button>
            </div>
        </div>

        <!-- Job List Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900">Offres d'emploi</h2>
                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600"
                    x-text="total + ' au total'">
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Titre / Mission</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Entreprise</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Compétences</th>

                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <template x-for="job in jobs" :key="job.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900" x-text="job.title"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <template x-if="job.image">
                                            <img :src="'/storage/' + job.image"
                                                class="h-8 w-8 rounded-lg object-cover mr-3 border border-gray-100">
                                        </template>
                                        <template x-if="!job.image">
                                            <div
                                                class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </template>
                                        <div class="text-sm text-gray-600" x-text="job.company"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="skill in job.skills.slice(0, 2)" :key="skill.id">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                                                x-text="skill.name"></span>
                                        </template>
                                        <template x-if="job.skills.length > 2">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-600"
                                                x-text="'+' + (job.skills.length - 2)"></span>
                                        </template>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-1">
                                        {{-- @can('update-job', $emploi) --}}
                                        <!-- Since we are in Alpine context, we handle permissions via backend validation or passing auth user data. For simplicity here, assuming admin sees buttons but actions are protected. Ideally pass permission flags in JSON. -->
                                        <button @click="openEditModal(job)" class="p-2 text-blue-600 rounded-lg"
                                            title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        {{-- @endcan --}}

                                        {{-- @can('delete-job', $emploi) --}}
                                        <button @click="deleteJob(job.id)" class="p-2 text-red-600 rounded-lg"
                                            title="Supprimer">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        {{-- @endcan --}}
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="jobs.length === 0">
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">Aucune offre trouvée</p>
                                        <p class="text-xs text-gray-500 mt-1">Commencez par ajouter une nouvelle mission.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            <!-- Simplified pagination for Alpine example - relying on load more or full list, but keeping it simple for now or implementing basic prev/next if API supports it -->
        </div>
    </div>

    <!-- Modal Backdrop -->
    <div x-data x-show="$store.jobModal.open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
        style="display: none;">

        <!-- Modal Content -->
        <div @click.outside="$store.jobModal.close()" x-show="$store.jobModal.open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col">

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900"
                    x-text="$store.jobModal.isEdit ? 'Modifier l\'offre' : 'Ajouter une offre'"></h2>
                <button @click="$store.jobModal.close()"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <div class="p-6 overflow-y-auto">
                <form @submit.prevent="$store.jobModal.submit()" class="space-y-5" enctype="multipart/form-data">
                    <input type="hidden" x-model="$store.jobModal.form.id">

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Titre du
                            poste</label>
                        <input type="text" id="title" x-model="$store.jobModal.form.title" required
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                            placeholder="ex: Développeur PHP Fullstack">
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-1.5">Entreprise</label>
                        <input type="text" id="company" x-model="$store.jobModal.form.company" required
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                            placeholder="Nom de la société">
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea id="description" x-model="$store.jobModal.form.description" rows="4" required
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm resize-none"
                            placeholder="Détaillez la mission et les pré-requis..."></textarea>
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">Logo ou Image</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Télécharger un fichier</span>
                                        <input id="image" @change="$store.jobModal.handleFile($event)" type="file"
                                            class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Compétences requises</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($skills as $skill)
                                <label
                                    class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all select-none group">
                                    <input type="checkbox" value="{{ $skill->id }}"
                                        x-model="$store.jobModal.form.skills"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span
                                        class="ml-2.5 text-sm text-gray-600 group-hover:text-gray-900">{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex gap-3 pt-5 border-t border-gray-100">
                        <button type="button" @click="$store.jobModal.close()"
                            class="flex-1 px-4 py-2.5 bg-white border border-gray-200 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" :disabled="$store.jobModal.loading"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-sm font-medium text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors disabled:opacity-50">
                            <span x-show="!$store.jobModal.loading">Enregistrer l'offre</span>
                            <span x-show="$store.jobModal.loading">Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('jobModal', {
                    open: false,
                    isEdit: false,
                    loading: false,
                    form: {
                        id: null,
                        title: '',
                        company: '',
                        description: '',
                        image: null,
                        skills: []
                    },

                    init() {
                        this.open = false;
                    },

                    resetForm() {
                        this.form = {
                            id: null,
                            title: '',
                            company: '',
                            description: '',
                            image: null,
                            skills: []
                        };
                        this.isEdit = false;
                        // Reset file input manually if needed
                        const fileInput = document.getElementById('image');
                        if (fileInput) fileInput.value = '';
                    },

                    openCreate() {
                        this.resetForm();
                        this.isEdit = false;
                        this.open = true;
                    },

                    openEdit(job) {
                        this.resetForm();
                        this.form.id = job.id;
                        this.form.title = job.title;
                        this.form.company = job.company;
                        this.form.description = job.description;
                        this.form.skills = job.skills.map(s => s.id.toString());
                        this.isEdit = true;
                        this.open = true;
                    },

                    close() {
                        this.open = false;
                    },

                    handleFile(event) {
                        this.form.image = event.target.files[0];
                    },

                    async submit() {
                        this.loading = true;
                        const formData = new FormData();
                        formData.append('title', this.form.title);
                        formData.append('company', this.form.company);
                        formData.append('description', this.form.description);
                        if (this.form.image) {
                            formData.append('image', this.form.image);
                        }
                        this.form.skills.forEach(skillId => {
                            formData.append('skills[]', skillId);
                        });

                        let url = '{{ route('emplois.store') }}';
                        let method = 'POST';

                        if (this.isEdit) {
                            url = `/emplois/${this.form.id}`;
                            formData.append('_method', 'PUT');
                        }

                        try {
                            const response = await fetch(url, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                // Reload jobs or notify success
                                const event = new CustomEvent('job-saved');
                                window.dispatchEvent(event);
                                this.close();
                            } else {
                                const data = await response.json();
                                alert(data.message || 'Une erreur est survenue');
                            }
                        } catch (error) {
                            console.error(error);
                            alert('Erreur de connexion');
                        } finally {
                            this.loading = false;
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection

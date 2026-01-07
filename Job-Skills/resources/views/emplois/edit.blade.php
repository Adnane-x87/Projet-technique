<x-layouts.app>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="p-4 sm:p-7">
                <div class="text-center mb-5">
                    <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Modifier l'offre</h1>
                </div>

                <form action="{{ route('emplois.update', $emploi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-y-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm mb-2 dark:text-white">Titre du poste</label>
                            <input type="text" id="title" name="title" value="{{ $emploi->title }}" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" required>
                        </div>

                        <!-- Company -->
                        <div>
                            <label for="company" class="block text-sm mb-2 dark:text-white">Entreprise</label>
                            <input type="text" id="company" name="company" value="{{ $emploi->company }}" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" required>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm mb-2 dark:text-white">Description</label>
                            <textarea id="description" name="description" rows="4" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400" required>{{ $emploi->description }}</textarea>
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm mb-2 dark:text-white">URL de l'image (logo)</label>
                            <input type="url" id="image" name="image" value="{{ $emploi->image }}" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                        </div>

                        <!-- Skills -->
                        <div>
                            <label class="block text-sm mb-2 dark:text-white">Compétences</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($skills as $skill)
                                    <div class="flex">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}" id="skill-edit-{{ $skill->id }}" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                            {{ $emploi->skills->contains($skill->id) ? 'checked' : '' }}>
                                        <label for="skill-edit-{{ $skill->id }}" class="text-sm text-gray-500 ml-2 dark:text-gray-400">{{ $skill->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end gap-x-2">
                             <a href="{{ route('admin.dashboard') }}" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-gray-200 font-medium text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-slate-900 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                Annuler
                            </a>
                            <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>

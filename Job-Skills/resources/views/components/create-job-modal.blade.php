@props(['skills'])

<div id="hs-create-job-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[60] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto pointer-events-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-zinc-900 dark:border-zinc-800">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h2 class="block text-2xl font-bold text-gray-800 dark:text-white">
                        Ajouter un emploi
                    </h2>
                </div>

                <div class="mt-5">
                    <form action="{{ route('emplois.store') }}" method="POST">
                        @csrf
                        <div class="grid gap-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm mb-2 dark:text-white">Titre du poste</label>
                                <div class="relative">
                                    <input type="text" id="title" name="title" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-300" required placeholder="Ex: Développeur Laravel">
                                </div>
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company" class="block text-sm mb-2 dark:text-white">Entreprise</label>
                                <div class="relative">
                                    <input type="text" id="company" name="company" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-300" required placeholder="Ex: Google">
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm mb-2 dark:text-white">Description</label>
                                <div class="relative">
                                    <textarea id="description" name="description" rows="3" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-300" required></textarea>
                                </div>
                            </div>
                            
                            <!-- Image URL -->
                             <div>
                                <label for="image" class="block text-sm mb-2 dark:text-white">Logo URL (Optionnel)</label>
                                <div class="relative">
                                    <input type="url" id="image" name="image" class="py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-300" placeholder="https://...">
                                </div>
                            </div>

                            <!-- Skills -->
                            <div>
                                <label class="block text-sm mb-2 dark:text-white">Compétences</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($skills as $skill)
                                    <div class="flex">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-zinc-900 dark:border-zinc-800 checked:bg-blue-500 checked:border-blue-500 focus:ring-offset-zinc-900" id="skill-{{ $skill->id }}">
                                        <label for="skill-{{ $skill->id }}" class="text-sm text-gray-500 ml-2 dark:text-zinc-400">{{ $skill->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-zinc-900">
                                Créer l'offre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="contactForm" action="{{ route('emplois.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
    @csrf
    <input type="text" name="title" required class="w-full border p-2 rounded" placeholder="Titre du poste">
    <input type="text" name="company" required class="w-full border p-2 rounded" placeholder="Nom de l'entreprise">
    <div class="text-xs text-gray-500">Logo:</div>
    <input type="file" name="image" class="w-full text-xs">
    <textarea name="description" rows="2" required class="w-full border p-2 rounded" placeholder="Description"></textarea>
    
    <div class="text-xs font-bold">Compétences:</div>
    <div class="border p-2 rounded max-h-32 overflow-y-auto grid grid-cols-2 gap-1 text-xs">
        @foreach ($skills as $skill)
            <label class="flex items-center gap-1">
                <input type="checkbox" name="skills[]" value="{{ $skill->id }}"> {{ $skill->name }}
            </label>
        @endforeach
    </div>

    <div class="flex justify-end gap-2 pt-2">
        <button type="button" class="close-btn border px-3 py-1 rounded">Annuler</button>
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Créer</button>
    </div>
</form>
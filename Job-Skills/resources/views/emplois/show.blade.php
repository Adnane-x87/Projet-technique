<x-layouts.app>
    <!-- Breadcrumb -->
    <p style="margin-bottom: 20px;">
        <a href="{{ route('emplois.index') }}">← Retour aux offres</a>
    </p>

    <div class="card">
        <!-- Header -->
        <div style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
            @if ($emploi->image)
                <img src="{{ asset('storage/' . $emploi->image) }}" alt="{{ $emploi->company }}"
                    style="width: 100%; height: auto; max-height: 500px; object-fit: cover; border-radius: 12px; margin-bottom: 25px;">
            @endif
            <h1 style="font-size: 1.75rem; margin-bottom: 5px;">{{ $emploi->title }}</h1>
            <p style="color: #666; font-size: 1.1rem;">{{ $emploi->company }}</p>


        </div>

        <!-- Description -->
        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 1.25rem; margin-bottom: 10px;">À propos du poste</h2>
            <p style="color: #444; line-height: 1.8; white-space: pre-line;">{{ $emploi->description }}</p>
        </div>

        <!-- Skills -->
        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 1.25rem; margin-bottom: 10px;">Compétences</h2>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach ($emploi->skills as $skill)
                    <span
                        style="background: #e0e0e0; padding: 5px 12px; border-radius: 4px; font-size: 14px;">{{ $skill->name }}</span>
                @endforeach
            </div>
        </div>


    </div>
</x-layouts.app>

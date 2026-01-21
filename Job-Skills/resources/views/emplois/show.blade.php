@extends('layouts.admin')

@section('content')
<div class="bg-white dark:bg-slate-900 shadow rounded-lg p-6 max-w-xl mx-auto border border-transparent dark:border-gray-700">

    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">{{ $emploi->title }}</h2>

    @if ($emploi->image)
        <div class="mb-6">
            <img src="{{ asset('storage/' . $emploi->image) }}" alt="{{ $emploi->company }}" class="w-full h-48 object-cover rounded-lg">
        </div>
    @endif

    <p class="text-gray-700 dark:text-gray-300 mb-2">
        <strong class="font-semibold">Entreprise :</strong> {{ $emploi->company }}
    </p>
    <p class="text-gray-700 dark:text-gray-300 mb-2">
        <strong class="font-semibold">Publiée le :</strong> {{ $emploi->created_at->translatedFormat('d M Y') }}
    </p>

    @if($emploi->skills->count() > 0)
    <div class="mb-4">
        <strong class="block font-semibold text-gray-700 dark:text-gray-300 mb-2">Compétences :</strong>
        <div class="flex flex-wrap gap-2">
            @foreach ($emploi->skills as $skill)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                    {{ $skill->name }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Description</h3>
        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $emploi->description }}</p>
    </div>

    <a href="{{ route('emplois.index') }}"
    class="mt-6 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 hover:underline dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux offres
    </a>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if(window.lucide) window.lucide.createIcons();
    });
</script>
@endsection

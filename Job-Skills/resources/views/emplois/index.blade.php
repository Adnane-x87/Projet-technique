@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Offres d'emploi</h1>
        <div id="success-msg" class="text-green-600 text-sm"></div>
    </div>

    <div class="flex gap-2 border p-2 rounded">
        <input type="text" id="search" class="border p-2 flex-grow rounded" placeholder="Rechercher...">
        <select id="skill-filter" class="border p-2 rounded">
            <option value="">Compétences</option>
            @foreach ($skills as $skill)
                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
            @endforeach
        </select>
        <button id="openModal" class="bg-blue-600 text-white px-4 py-2 rounded">Ajouter</button>
    </div>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-100 text-left text-sm">
                <th class="border p-2">Logo</th>
                <th class="border p-2">Poste</th>
                <th class="border p-2">Entreprise</th>
                <th class="border p-2">Compétences</th>
            </tr>
        </thead>
        <tbody id="jobs-grid">
            @include('emplois._table_body')
        </tbody>
    </table>
</div>

@include('emplois._modal')

@push('scripts')
<script>
    window.CONTACT_ROUTES = { search: "{{ route('emplois.search') }}" };
</script>
<script src="{{ asset('js/Emploi.js') }}"></script>
@endpush
@endsection

@foreach ($emplois as $emploi)
<tr>
    <td class="border p-2 w-12 text-center">
        @if($emploi->image)
            <img src="{{ asset('storage/' . $emploi->image) }}" class="w-8 h-8 mx-auto rounded">
        @else
            <div class="w-8 h-8 bg-gray-200 mx-auto rounded text-xs flex items-center justify-center">{{ substr($emploi->company, 0, 1) }}</div>
        @endif
    </td>
    <td class="border p-2">{{ $emploi->title }}</td>
    <td class="border p-2 text-gray-600">{{ $emploi->company }}</td>
    <td class="border p-2">
        @foreach ($emploi->skills as $skill)
            <span class="bg-gray-100 px-1 text-xs rounded">{{ $skill->name }}</span>
        @endforeach
    </td>
</tr>
@endforeach

@if($emplois->isEmpty())
<tr>
    <td colspan="4" class="p-4 text-center text-gray-400">Aucun résultat</td>
</tr>
@endif
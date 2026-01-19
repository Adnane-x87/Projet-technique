<div id="contactModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <div class="flex justify-between mb-4">
            <h2 class="font-bold">Nouvel Emploi</h2>
            <button class="close-btn text-gray-500">&times;</button>
        </div>
        @include('emplois._form')
    </div>
</div>
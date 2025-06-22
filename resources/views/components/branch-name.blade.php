@props(['branchName'])

@if ($branchName != 'main')
    <div class="fixed bottom-16 left-1/2 transform -translate-x-1/2 bg-white px-2 border border-gray-300 rounded shadow-lg cursor-not-allowed">
        {{ $branchName }}
    -</div>
@endif
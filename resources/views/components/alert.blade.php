@props(['type' => 'success'])

@php
    $colors = [
        'success' => 'bg-green-100 text-green-800',
        'error' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'logout' => 'bg-orange-100 text-orange-800',
    ];
@endphp

<div class="{{ $colors[$type] }} px-4 py-2 rounded mb-4">
    {{ $slot }}
</div>

@props(['link' => false])

@if($link)
    <span {{ $attributes->merge(['class' => 'inline-block rounded border border-indigo-600 text-indigo-600 px-12 py-3 text-sm font-medium bg-white hover:bg-indigo-600 hover:text-white focus:outline-none focus:ring active:bg-indigo-500']) }}>
        {{ $slot }}
    </span>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-block rounded border border-indigo-600 text-indigo-600 px-12 py-3 text-sm font-medium bg-white hover:bg-indigo-600 hover:text-white focus:outline-none focus:ring active:bg-indigo-500']) }}>
        {{ $slot }}
    </button>
@endif
<div>
    <label for="{{ $name }}" class="block text-sm text-start font-medium text-gray-900 mb-1">
        {{ $label }}
    </label>
    <input 
        type="{{ $type }}"
        name="{{ $name }}" 
        id="{{ $name }}"
        required
        {{ $attributes->merge(['class' => 'border border-gray-300 text-black focus:outline-none focus:border-blue-600 rounded-md p-2 w-full']) }}
    />
</div>
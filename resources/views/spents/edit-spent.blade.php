<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-page-custom">
            <x-header :user="$user"></x-header>
            
            <main class="container mx-auto p-4">
                <form method="POST" action="{{ route('spents.update', $spent) }}">
                @csrf
                @method('PUT')
                    <div class="w-full md:w-5/6 mx-auto">
                        <div class="p-2 bg-white rounded-md">
                            <!-- Fecha de compra -->
                            <div class="mt-4">
                                <label for="expense_date" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                                    <input
                                        type="date"
                                        id="expense_date"
                                        name="expense_date"
                                        value="{{ $spent->expense_date }}"
                                        required
                                        class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900"
                                        placeholder="Dia"
                                    />
                                    <span class="pointer-events-none absolute left-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
                                        Dia de la compra
                                    </span>
                                </label>
                                @error('expense_date')
                                <p>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                            <!-- Nombre del gasto -->
                            <div class="mt-4">
                                <label for="spentName" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                                    <input
                                        type="text"
                                        id="spentName"
                                        name="spentName"
                                        value="{{ $spent->name }}"
                                        required
                                        class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900"
                                        placeholder="Nombre del Gasto"
                                    />
                                    <span class="pointer-events-none absolute left-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
                                        Nombre del Gasto
                                    </span>
                                </label>
                                @error('spentName')
                                <p>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="mt-4">
                                <label for="price" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                                    <input
                                        type="number"
                                        step="0.01"
                                        id="price"
                                        name="price"
                                        value="{{ $spent->price }}"
                                        required
                                        class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900"
                                        placeholder="Precio"
                                    />
                                    <span class="pointer-events-none absolute left-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
                                        Precio
                                    </span>
                                </label>
                                @error('price')
                                <p>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="container mx-auto p-4">
                        <div class="w-full md:w-5/6 mx-auto">
                            <section class="flex justify-between">
                                <a href="{{ route('dashboard') }}" id="back-button">
                                    <x-button-add>
                                        Volver
                                    </x-button-add>
                                </a>
                                <button type="submit" class="px-2 inline-flex text-xl leading-5 px-4 py-2 rounded-lg font-semibold bg-blue-100 text-blue-800">
                                    Editar
                                </button>
                            </section>
                        </div>
                    </div>
                </form>
            </main>
        </div>

        @stack('modals')

        @livewireScripts

    </body>
    <script src="{{ mix('/js/app.js') }}" defer></script>
</html>
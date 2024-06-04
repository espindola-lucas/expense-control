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
        <div class="min-h-screen bg-gray-100">
            <x-header></x-header>

            <main class="container mx-auto p-4">
                <div class="w-full md:w-5/6 mx-auto">
                    <div class="flex justify-start mb-4">
                        <a href="{{ route('products.create') }}">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                    </div>
                    <div class="p-2 bg-white shadow-md">
                        <table class="table-auto w-full border-collapse">
                            <!-- head -->
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2">{{ __('Name') }}</th>
                                    <th class="px-4 py-2">{{ __('Product') }}</th>
                                    <th class="px-4 py-2">{{ __('Price') }}</th>
                                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr class="bg-base-200 border-b">
                                    <td class="px-4 py-2 text-center">{{ $user }}</td>
                                    <td class="px-4 py-2 text-center">{{ $product->name }}</td>
                                    <td class="px-4 py-2 text-center">$ {{ $product->price }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-green-600 hover:text-indigo-900">
                                                Editar
                                            </a>
                                        </span>
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline">
                                            @method('DELETE')
                                            @csrf
                                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100">
                                                <button type="submit" class="text-red-600 hover:text-indigo-900">
                                                    Eliminar
                                                </button>
                                            </span>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-between mt-4 p-2 bg-white shadow-md">
                        <p>
                            Plata disponible : 200.000
                        </p>
                        <p>
                            Total de gastos : {{ $totalPrice }}
                        </p>
                    </div>
                </div>
            </main>
        </div>
        <x-footer>
            <x-slot name="namewebapp">
                Expense Control
            </x-slot>
        </x-footer>
        @stack('modals')

        @livewireScripts
    </body>
</html>

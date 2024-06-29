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
                    <div class="flex justify-between mb-4">
                        <a href="{{ route('products.create') }}">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 -mt-8 hidden sm-500:flex">
                            <div class="flex items-center space-x-4">
                                <div class="mt-4">
                                    <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                    <select id="month" name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option selected disabled>Elegi el mes</option>
                                        @foreach($months as $month)
                                        <option value="{{ $month->value }}" {{ $month->value == $selectedMonth ? 'selected' : '' }}>{{ $month->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                    <select id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option selected disabled>Elegi el año</option>
                                        @foreach($years as $year)
                                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white rounded-lg py-2 px-4 self-end">Filtrar</button>
                        </form>
                    </div>
                    <form action="" method="GET" class="flex space-x-4 -mt-8 flex sm-500:hidden">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                <select id="month" name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected disabled>Elegi el mes</option>
                                    @foreach($months as $month)
                                    <option value="{{ $month->value }}" {{ $month->value == $selectedMonth ? 'selected' : '' }}>{{ $month->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                <select id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected disabled>Elegi el año</option>
                                    @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white rounded-lg py-2 px-4 self-end">Filtrar</button>
                    </form>
                    <div class="p-2 bg-white shadow-md">
                        <table class="table-auto w-full border-collapse">
                            <!-- head -->
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 hidden sm-500:table-cell">{{ __('User') }}</th>
                                    <th class="px-4 py-2">{{ __('Date') }}</th>
                                    <th class="px-4 py-2">{{ __('Product') }}</th>
                                    <th class="px-4 py-2">{{ __('Price') }}</th>
                                    <th class="px-4 py-2">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr class="bg-base-200 border-b">
                                    <td class="px-4 py-2 text-center hidden sm-500:table-cell">{{ $user }}</td>
                                    <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::createFromFormat('d/m/Y', $product->expense_date)->format('d/m') }}</td>
                                    <!-- <td class="px-4 py-2 text-center hidden">{{ \Carbon\Carbon::createFromFormat('d/m/Y', $product->expense_date)->format('d/m') }}</td> -->
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
                    <div class="flex justify-between mt-4 p-2 bg-white shadow-md py-2">
                        <p class="text-green-800">
                            Plata disponible: {{ $available_money }}
                        </p>
                        <p class="text-red-800">
                            Total de gastos: {{ $totalPrice }}
                        </p>
                        <p class="text-blue-800">
                            Resto de plata disponible: {{ $available_money - $totalPrice }}
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

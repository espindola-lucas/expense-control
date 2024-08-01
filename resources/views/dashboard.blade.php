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
                <div class="w-11/12 mx-auto">
                    <div class="flex justify-between mb-8 sm-500:mb-4">
                        <a href="{{ route('products.create') }}">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 -mt-8 hidden sm-500:flex">
                            <div class="flex items-center space-x-4">
                                @unless(!$lastConfiguration)
                                    @unless(is_null($lastConfiguration->start_counting) || is_null($lastConfiguration->end_counting))
                                        <div class="text-black py-2 px-4 self-end">Periodo: {{ $lastConfiguration->start_counting }} - {{ $lastConfiguration->end_counting }}
                                        </div>
                                    @elseif(!is_null($lastConfiguration->start_counting))
                                        <div class="text-black py-2 px-4 self-end">Periodo: {{ $lastConfiguration->start_counting }} - Sin corte</div>
                                    @else
                                        <div class="text-black py-2 px-4 self-end">Periodo: Sin inicio - {{ $lastConfiguration->end_counting }}</div>
                                    @endunless
                                @endunless
                                <div class="mt-4">
                                    <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                    <select id="month" name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option selected disabled>Elegi el mes</option>
                                        @foreach($months as $month)
                                        <option value="{{ $month->value }}" {{ $month->value == $selectedMonth ? 'selected' : '' }}>{{ $month->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                    <select id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                    <!-- reponsive -->
                    <div class="flex flex-col space-y-2 -mt-8 sm-500:hidden">
                        <form action="" method="GET" class="flex space-x-4">
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
                        @unless(!$lastConfiguration)
                            @unless(is_null($lastConfiguration->start_counting) || is_null($lastConfiguration->end_counting))
                                <div class="text-black py-2 px-4">Periodo: {{ $lastConfiguration->start_counting }} - {{ $lastConfiguration->end_counting }}</div>
                            @elseif(!is_null($lastConfiguration->start_counting))
                                <div class="text-black py-2 px-4">Periodo: {{ $lastConfiguration->start_counting }} - Sin corte</div>
                            @else
                                <div class="text-black py-2 px-4">Periodo: Sin inicio - {{ $lastConfiguration->end_counting }}</div>
                            @endunless
                        @endunless
                    </div>
                    <div class="flex flex-col flex-wrap lg:grid lg:grid-cols-3 xl:grid xl:grid-cols-5">
                        @foreach($products as $product)
                            <div class="flex flex-col bg-white my-2 mx-1 h-48 rounded-md py-4 px-6 xl:w-60">
                                <h3 class="text-center font-bold text-xl text-gray-800 pb-2">{{ $product->name }}</h3>
                                <h3 class="text-base font-semibold text-gray-900">$ {{ $product->price }}</h3>
                                <p class="text-sm text-gray-500 pb-3"></p>
                                <div class="flex gap-2 text-sm text-gray-500 border-b pb-2">
                                    <p class="">{{ __('last update') }}:</p>
                                    <p>{{ \Carbon\Carbon::createFromFormat('d/m/Y', $product->expense_date)->format('d/m/y') }}</p>
                                </div>
                                <div class="flex justify-around items-center py-3">
                                    <div class="flex gap-2 text-gray-600 hover:scale-110 duration-200 hover:cursor-pointer">
                                        <svg class="w-6 stroke-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        <a href="{{ route('products.edit', $product->id) }}">
                                            <button class="font-semibold text-sm text-green-700">Editar</button>
                                        </a>
                                    </div>
                                    <div class="flex gap-2 text-gray-600 hover:scale-110 duration-200 hover:cursor-pointer">
                                        <svg class="w-6 stroke-red-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline">
                                            @method('DELETE')
                                            @csrf
                                            <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                                            <button class="font-semibold text-sm text-red-700">Eliminar</button>
                                            <form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- div grande -->
                    <div class="hidden lg:flex justify-between mt-4 p-2 bg-white shadow-md py-2">
                        <p class="text-green-800">
                            Plata disponible: {{ $available_money }}
                        </p>
                        <p class="text-red-800">
                            Total de gastos: {{ $totalPrice }}
                        </p>
                        <p class="text-blue-800">
                            Resto de plata disponible: {{ $rest_money }}
                        </p>
                    </div>
                    <!-- div chico -->
                    <div class="bg-white shadow-md py-2 lg:hidden xl:hidden">
                        <h1 class="text-center font-bold text-xl text-gray-800 pb-2">Dinero</h1>
                        <div class="flex justify-between">
                            <p class="text-green-800">
                                Disponible: {{ $available_money }}
                            </p>
                            <p class="text-red-800">
                                Gastos: {{ $totalPrice }}
                            </p>
                            <p class="text-blue-800">
                                Resto: {{ $rest_money }}
                            </p>
                        </div>
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

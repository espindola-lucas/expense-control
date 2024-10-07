<!DOCTYPE html>
<html lang="en">
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
    <body>
        <div class="min-h-screen bg-page-custom">
            <x-header :user="$user"></x-header>
            <main class="container mx-auto p-4">
                <form method="POST" action="{{ route('configuration.store') }}">
                    @csrf
                    <div class="w-full md:w-5/6 mx-auto">
                        <div class="p-2 bg-white shadow-md rounded-md">
                            <div class="mt-4">
                                <label for="start_counting" class="block mb-2">
                                    Configure la fecha de inicio del periodo de conteo de gastos.
                                    <input id="start_counting" name="start_counting" type="date" value="{{ old('start_counting') }}"
                                        class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                            </div>
                            <div class="mt-4">
                                <label for="end_counting" class="block mb-2">
                                    Configure la fecha de fin del periodo de conteo de gastos.
                                    <input id="end_counting" name="end_counting" type="date" value="{{ old('end_counting') }}"
                                        class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                            </div>
                            <div class="mt-4">
                                <label for="available_money" class="block mb-2">
                                    Configure la cantidad de plata disponible para el mes, <br>
                                    sin puntos ni comas.
                                    <input id="available_money" name="available_money" type="number" value="{{ old('available_money') }}"
                                    class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                                @error('available_money')
                                <p class="text-red-800">
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                            <div class="mt-">
                                <label for="month_available_money" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                <select id="month_available_money" name="month_available_money" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 sm-500:w-1/2">
                                    <option selected disabled>Elegi el mes correspondiente al monto disponible</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month->value }}">{{ $month->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="expense_percentage_limit" class="block mb-2">
                                    Coloque el porcentaje (%) del umbral.
                                    <input id="expense_percentage_limit" name="expense_percentage_limit" type="number" value="{{ old('expense_percentage_limit') }}"
                                    class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                            </div>
                            <div class="mt-4">
                                <div class="form-control w-full sm-500:w-1/2">
                                    Que filtros quieres que aparezcan en el dashboard?
                                    <br>
                                    <label for="filter" class="flex justify-around">
                                        <div>
                                            <span class="label-text">Año</span>
                                            <input id="year" name="filter" type="checkbox" name="filter" value="year" class="checkbox checkbox-primary cursor-pointer" onclick="handleCheckboxClick(this)"/>
                                        </div>
                                        <div>
                                            <span class="label-text">Mes</span>
                                            <input id="month" name="filter" type="checkbox" name="filter" value="month" class="checkbox checkbox-primary cursor-pointer" onclick="handleCheckboxClick(this)"/>
                                        </div>
                                        <div>
                                            <span class="label-text">Ambos</span>
                                            <input id="both" name="filter" type="checkbox" name="filter" value="both" class="checkbox checkbox-primary cursor-pointer" checked onclick="handleCheckboxClick(this)"/>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container mx-auto p-4">
                        <div class="w-full md:w-5/6 mx-auto flex justify-end">
                            <button type="submit" class="px-2 inline-flex text-xl leading-5 px-4 py-2 rounded-lg font-semibold bg-blue-100 text-blue-800">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </body>
<script src="{{ mix('/js/app.js') }}" defer></script>
</html>
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
            <form method="POST" action="{{ route('fixedexpenses.store') }}">
                @csrf
                <div class="w-full md:w-5/6 mx-auto">
                    <div class="p-2 bg-white shadow-md rounded-md">
                        <div class="mt-4">
                            <label for="name_fixed_expense" class="block mb-2">
                                Nombre del gasto fijo.
                                <input id="name_fixed_expense" name="name_fixed_expense" type="text" value="{{ old('name_fixed_expense') }}"
                                class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                            </label>
                        </div>
                        <div class="mt-4">
                            <label for="value_fixed_expense" class="block mb-2">
                                Valor del gasto fijo.
                                <input id="value_fixed_expense" name="value_fixed_expense" type="number" value="{{ old('value_fixed_expense') }}"
                                class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                            </label>
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
    <x-footer :nameWebApp="$footerInformation['textInformation']" :currentYear="$footerInformation['year']" />
</body>
</html>
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
            <div class="container mx-auto p-4">
                <div class="p-2 bg-white rounded-md">
                    <div class="mt-4">
                        <label for="start_counting" class="block mb-2 text-sm text-sm">
                            Configure la fecha de inicio del periodo de conteo de gastos.
                            <input id="start_counting" name="start_counting" type="date" value="{{ $configuration['start_counting'] }}" @unless(is_null($configuration['start_counting'])) disabled @endunless
                                class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2 @unless(is_null($configuration['start_counting'])) bg-gray-200 @endunless"/>
                        </label>
                    </div>
                    <div class="mt-4">
                        <label for="end_counting" class="block mb-2 text-sm">
                            Configure la fecha de fin del periodo de conteo de gastos.
                            <input id="end_counting" name="end_counting" type="date" value="{{ $configuration['end_counting'] }}" @unless(is_null($configuration['end_counting'])) disabled @endunless
                                class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2 @unless(is_null($configuration['end_counting'])) bg-gray-200 @endunless"/>
                        </label>
                    </div>
                </div>
                <div class="container mx-auto p-4">
                    <div class="w-full md:w-5/6">
                        <a href="{{ route('configuration.index') }}" id="back-button">
                            <x-button-add>
                                Volver
                            </x-button-add>
                        </a>
                    </div>
                </div>
            </div>
        </main>

    </div>        
    @stack('modals')
    
    @livewireScripts
</body>
@vite('resources/js/app.js')
</html>

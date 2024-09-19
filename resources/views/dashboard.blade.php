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

            @if($message)
                <x-notification class="w-full text-red-700 bg-red-100">
                    <x-slot name="message">
                        Apa, ya gastaste mas del 85% de la plata del mes.
                    </x-slot>
                </x-notification>
            @else
                <x-notification class="w-full text-green-700 bg-green-100">
                    <x-slot name="message">
                        Tranquilo maquina, todavía hay plata para gastar. <br>
                        Llevas usado {{ $percentageUsed }}% de la plata del mes.
                    </x-slot>
                </x-notification>
            @endif

            <main class="container mx-auto p-4">
                <div class="w-11/12 mx-auto">
                    <div class="flex justify-between sm-500:mb-4">
                        <a href="{{ route('products.create') }}">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                        <div class="flex items-center space-x-4">
                            Fecha: {{ $currentDate }}
                        </div>
                        
                        <div class="hidden sm-500:block">
                            <x-period-display :lastConfiguration="$lastConfiguration"/>
                        </div>

                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 -mt-8 hidden sm-500:flex">
                            <x-filter-dropdown :months="$months" :years="$years" :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" />
                            <x-button-filter/>
                        </form>
                    </div>

                    <!-- Responsive -->
                    <div class="flex flex-col space-y-2 sm-500:hidden">
                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 justify-center items-center">
                            <x-filter-dropdown :months="$months" :years="$years" :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" />
                            <x-button-filter />
                        </form>
                        <x-period-display :lastConfiguration="$lastConfiguration" />
                    </div>
                    
                    <x-product-card :products="$products" :selectedMonth="$selectedMonth" :selectedYear="$selectedMonth"/>

                    <div class="bg-white shadow-md py-2 rounded-md p-2 lg:py-4 lg:flex lg:justify-between lg:rounded-none">
                        <p class="text-center text-green-800">
                            <span class="lg:hidden">Disponible:</span>
                            Plata disponible: {{ $available_money }}
                        </p>
                        <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
                        <p class="text-center text-red-800">
                            <span class="lg:hidden">Gastos:</span>
                            Total de gastos: {{ $totalPrice }}
                        </p>
                        <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
                        <p class="text-center text-blue-800">
                            <span class="lg:hidden">Resto:</span>
                            Resto de plata disponible: {{ $rest_money }}
                        </p>
                    </div>
                </div>
            </main>
        </div>
        <x-footer :nameWebApp="$footerInformation['textInformation']" :currentYear="$footerInformation['year']" />
        @stack('modals')

        @livewireScripts
    </body>
</html>

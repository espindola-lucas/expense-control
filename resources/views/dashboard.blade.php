<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Expense Control') }}</title>

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

            @if($message)
                <x-notification class="w-full text-red-700 bg-red-100">
                    <x-slot name="message">
                        Apa, ya gastaste mas del {{ $lastConfiguration['expense_percentage_limit'] }}% de la plata del mes. <br>
                        Porcentaje usado: {{ $percentageUsed['percentageUser'] }}%
                    </x-slot>
                </x-notification>
            @endif
            <main class="container mx-auto p-4">
                <div class="w-11/12 mx-auto">
                    <div class="flex justify-between sm-500:mb-4">
                        <a href="{{ route('spents.create') }}" id="add-expense">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                        <div class="flex items-center space-x-4 text-white">
                            Fecha: {{ $currentDate }}
                        </div>
                        
                        <div class="hidden sm-500:block">
                            <x-period-display :lastConfiguration="$lastConfiguration"/>
                        </div>

                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 -mt-8 hidden sm-500:flex">
                            <x-filter-dropdown :allPeriods="$allPeriods"/>
                            <x-button-filter/>
                        </form>
                    </div>

                    <!-- Responsive -->
                    <div class="flex flex-col space-y-2 sm-500:hidden">
                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 justify-center items-center">
                            <x-filter-dropdown :allPeriods="$allPeriods"/>
                            <x-button-filter/>
                        </form>
                        <x-period-display :lastConfiguration="$lastConfiguration" />
                    </div>

                    <x-monthly-balance 
                        :availableMoney="$monthly_balance['available_money']"
                        :totalPrice="$monthly_balance['total_price']"
                        :restMoney="$monthly_balance['rest_money']"
                        :countSpent="$monthly_balance['count_spent']"
                    />

                    <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 mt-4">
                        <div class="bg-{{$percentageUsed['color']}}-700 h-6 rounded-full text-sm text-center text-white"
                             style="width: calc({{ $percentageUsed['percentageUser'] }}%); max-width: 100%;">
                             {{ $percentageUsed['percentageUser'] }}%
                        </div>
                    </div>
                    
                    <x-spent-card :spents="$spents"/>

                    <div class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-white px-2 border border-gray-300 rounded shadow-lg cursor-not-allowed">
                        {{ $branchName }}
                    </div>

                </div>
            </main>
        </div>
        <x-footer 
            :nameWebApp="$footerInformation['textInformation']" 
            :currentYear="$footerInformation['year']" 
        />
        @stack('modals')

        @livewireScripts
    </body>
</html>

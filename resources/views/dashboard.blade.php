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
            
            <!-- personal -->
            @if($type === 'personal')
                @if($message)
                    <x-notification class="w-full text-red-700 bg-red-100">
                            <x-slot name="message">
                                Apa, ya gastaste mas del {{ $lastConfiguration['expense_percentage_limit'] }}% de la plata del mes. <br>
                                Porcentaje usado: {{ $percentageUsed['percentageUser'] }}%
                            </x-slot>
                        </x-notification>
                @endif
            @endif

            <!-- personal & busines -->
            <main class="container mx-auto p-4 mb-14">
                @if($hasConfiguration)
                    @if($hasBothConfig)
                        <!-- choose information to display -->
                        <div class="flex w-11/12 mx-auto space-x-4 mb-4 justify-evenly">
                            <a href="{{ route('dashboard', ['type' => 'personal']) }}" class="bg-green-600 text-white px-4 py-2 rounded">Personal</a>
                            <a href="{{ route('dashboard', ['type' => 'business']) }}" class="bg-green-600 text-white px-4 py-2 rounded">Negocio</a>
                        </div>
                    @endif
                
            
                    <!-- "navbar" 2 -->
                    <div class="w-11/12 mx-auto">
                        <div class="flex justify-between sm-500:mb-4">
                            <a href="{{ route($type === 'personal' ? 'spents.create' : 'sells.create', ['type' => $type]) }}" id="add-expense">
                                <x-button-add>
                                    Agregar
                                </x-button-add>
                            </a>

                            <div class="flex items-center space-x-4 text-white">
                                Fecha: {{ $currentDate }}
                            </div>

                            <div class="hidden sm-500:block">
                                @if($type === 'personal')
                                    <x-period-display :lastConfiguration="$lastConfiguration"/>
                                @endif
                            </div>

                            <!-- filters -->
                            <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 -mt-8 hidden sm-500:flex">
                                <x-filter-dropdown :allPeriods="$allPeriods"/>
                                <x-button-filter/>
                            </form>
                        </div>

                        @if($type === 'personal')
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

                            <x-spent-card :spents="$spents" />
                        @endif

                        @if($type === 'business')
                            <x-sell-card :sells="$sells" />
                        @endif
                    </div>
                @else
                    <x-empty-configuration-message :user="$user" />
                @endif

                <x-branch-name :branchName="$branchName" />
            </main>
        </div>
        <x-buttom-nav></x-buttom-nav>
        @stack('modals')

        @livewireScripts
    </body>
</html>
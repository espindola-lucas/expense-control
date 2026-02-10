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
            <x-header></x-header>
            
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
                    <!-- "navbar" 2 -->
                    <div class="w-11/12 mx-auto">

                        <x-btn-add-show-period
                            :currentDate="$currentDate"
                            :type="$type"
                            :lastConfiguration="$lastConfiguration"
                        />
                        <!-- filters -->
                        <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-4 justify-between sm-500:-mt-6">
                            <x-search-text />
                            <x-button-filter/>
                        </form>

                        @if(isset($spents) && !$onlyFilter)
                            <x-monthly-balance
                                :availableMoney="$monthly_balance['avalaibleMoney']"
                                :totalPrice="$monthly_balance['totalPrice']"
                                :restMoney="$monthly_balance['restMoney']"
                                :countSpent="$monthly_balance['countSpent']"
                            />

                            <div role="progressbar" aria-valuenow="{{ $percentageUsed['percentageUser'] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="flex justify-between gap-4">
                                  <span class="text-sm text-white font-semibold">Gastando...</span>

                                  <span class="text-sm text-white font-semibold">{{ $percentageUsed['percentageUser'] }}%</span>
                                </div>

                                <div class="mt-2 mb-2 w-full border-2 border-black bg-white p-1 shadow-[2px_2px_0_0]">
                                  <div class="h-3 bg-{{ $percentageUsed['color'] }}-600" style="width: {{ $percentageUsed['percentageUser'] }}%; max-width: 100%;"></div>
                                </div>
                            </div>

                            <x-spent-card :spents="$spents" />
                        @endif

                        @if($onlyFilter)
                            <p class="text-white text-lg text-center">Resultados de la busqued por texto</p>
                            <x-spent-card :spents="$spents" />
                        @endif
                    </div>
                @else
                    <x-empty-configuration-message :user="$user" />r
                @endif

                <x-branch-name :branchName="$branchName" />
            </main>
        </div>
        <x-buttom-nav></x-buttom-nav>
        @stack('modals')

        @livewireScripts
    </body>
</html>
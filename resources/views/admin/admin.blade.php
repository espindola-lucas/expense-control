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
            <main class="w-full p-4">
                <div class="w-full">
                    <x-admin :users="$users" :server="$server"></x-admin>
                </div>
            </main>
        </div>
        <!-- <x-footer 
            :nameWebApp="$footerInformation['textInformation']" 
            :currentYear="$footerInformation['year']" 
        /> -->
        @stack('modals')

        @livewireScripts
    </body>
</html>

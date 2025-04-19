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

            <main class="container mx-auto p-4 mb-14">
                <div class="w-11/12 mx-auto">
                    <a href="{{ route('configuration.create') }}">
                        <x-button-add>
                            Agregar
                        </x-button-add>
                    </a>
                    @if(!$configurations->isEmpty())
                        <x-configuration-list :configurations="$configurations" />
                    @endif
                </div>
            </main>
        </div>
        <x-buttom-nav></x-buttom-nav>
        <x-footer :nameWebApp="$footerInformation['textInformation']" :currentYear="$footerInformation['year']" />
        @stack('modals')

        @livewireScripts
    </body>
    @vite('resources/js/app.js')
</html>

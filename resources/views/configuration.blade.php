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
                    @if($hasPersonalConfiguration)
                        <a href="{{ route('personal-configuration.create') }}">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                        <x-configuration-list :config="$personalConfig" type="personal"/>
                    @else
                        <div class="bg-white p-4 sm:p-6 flex flex-col justify-center items-center rounded-lg mt-4">
                            <h1 class="text-xl text-center">Hola, <span class="font-semibold text-blue-700">{{ $user->name }}</span>!</h1>
                            <p class="mt-2 mb-4 text-base text-center">
                                Parece que no tenes ninguna <strong>configuración personal.</strong> <br>
                                Toca el siguiente boton para empezar!
                            </p>
                            <a href="{{ route('personal-configuration.create') }}" class="inline-flex items-center w-auto text-sm text-white bg-blue-500 py-2 px-4 rounded-lg">
                                <span>Agregar</span>
                            </a>
                        </div>
                    @endif

                    @if($hasBusinessConfiguration)
                        <a href="{{ route('business-configuration.create') }}">
                            <x-button-add>
                                Agregar
                            </x-button-add>
                        </a>
                        <x-configuration-list :config="$businessConfig" type="business"/>
                    @else
                        <div class="bg-white p-4 sm:p-6 flex flex-col justify-center items-center rounded-lg mt-4">
                            <h1 class="text-xl text-center">Hola, <span class="font-semibold text-blue-700">{{ $user->name }}</span>!</h1>
                            <p class="mt-2 mb-4 text-base text-center">
                                Parece que no tenes ninguna <strong>configuración de negocio.</strong> <br>
                                Toca el siguiente boton para empezar!
                            </p>
                            <a href="{{ route('business-configuration.create') }}" class="inline-flex items-center w-auto text-sm text-white bg-blue-500 py-2 px-4 rounded-lg">
                                <span>Agregar</span>
                            </a>
                        </div>
                    @endif
                </div>
            </main>
        </div>
        <x-branch-name :branchName="$branchName"/>
        <x-buttom-nav></x-buttom-nav>
        <x-footer :nameWebApp="$footerInformation['textInformation']" :currentYear="$footerInformation['year']" />
        @stack('modals')

        @livewireScripts
    </body>
    @vite('resources/js/app.js')
</html>

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
                <form method="POST" action="{{ route('spents.update', $spent) }}">
                @csrf
                @method('PUT')
                    <div class="w-full md:w-5/6 mx-auto">
                        <div class="p-2 bg-white rounded-md">
                            <!-- Fecha de compra -->
                            <div class="mt-4">
                                <x-form-input 
                                    type="date" 
                                    id="expense_date"
                                    name="expense_date"
                                    value="{{ $spent->expense_date }}"
                                    label="Día de la compra"
                                    required>
                                </x-form-input>
                                @error('expense_date')
                                <p>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                            <!-- Nombre del gasto -->
                            <div class="mt-4">
                                <x-form-input 
                                    type="text" 
                                    id="name"
                                    name="name"
                                    value="{{ $spent->name }}"
                                    label="Nombre del gasto" 
                                    required>
                                </x-form-input>
                                @error('name')
                                <p>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="mt-4">
                                <x-form-input 
                                    type="number"
                                    step="0.01"
                                    id="price"
                                    name="price"
                                    value="{{ $spent->price }}"
                                    label="Precio" 
                                    required>
                                </x-form-input>
                                @error('price')
                                <p>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="container mx-auto p-4">
                        <div class="w-full md:w-5/6 mx-auto">
                            <section class="flex justify-between">
                                <a href="{{ route('dashboard') }}" id="back-button">
                                    <x-button-add>
                                        Volver
                                    </x-button-add>
                                </a>
                                <button type="submit" class="px-2 inline-flex text-xl leading-5 px-4 py-2 rounded-lg font-semibold bg-blue-100 text-blue-800">
                                    Editar
                                </button>
                            </section>
                        </div>
                    </div>
                </form>
            </main>
        </div>

        @stack('modals')

        @livewireScripts

    </body>
    @vite('resources/js/app.js')
</html>
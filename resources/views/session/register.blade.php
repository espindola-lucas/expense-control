<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite('resources/css/app.css')
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="min-h-screen flex bg-page-custom flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-semi-white shadow-md overflow-hidden sm:rounded-lg">
                <form method="POST" action="{{ route('session-register.store') }}">
                @csrf
                    <div>
                        <x-form-input
                            type="text"
                            id="name"
                            name="name"
                            label="Nombre"
                        ></x-form-input>
                        @error('name')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-form-input
                            type="text"
                            id="email"
                            name="email"
                            label="Email"
                        ></x-form-input>
                        @error('email')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-form-input
                            type="password"
                            id="password"
                            name="password"
                            label="Contraseña"
                        ></x-form-input>
                        @error('password')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <x-form-input
                            type="password"
                            id="password"
                            name="password_confirmation"
                            label="Confirmar Contraseña"
                        ></x-form-input>
                        @error('password_confirmation')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>
                        <x-button>
                            {{ __('Register') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

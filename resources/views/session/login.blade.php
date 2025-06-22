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
        <div class="min-h-screen flex flex-col bg-page-custom sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-semi-white shadow-md overflow-hidden sm:rounded-lg">
                @if(session('error'))
                <x-alert type="error">
                    {{ session('error') }}
                </x-alert>
                @endif
                <form method="POST" action="{{ route('login.authenticate') }}">
                    @csrf
                    <div>
                        <x-form-input
                            type="email"
                            id="email"
                            name="email"
                            label="Email"
                            value="{{ old('email') }}"
                        ></x-form-input>
                    </div>

                    <div class="mt-4">
                        <x-form-input
                            type="password"
                            id="password"
                            name="password"
                            label="Contraseña"
                        ></x-form-input>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="flex items-center">
                            <input type="checkbox" id="remember_me" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"/>
                            <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="ms-2 text-sm text-gray-600">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-button id="login">
                            Iniciar Sesion
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

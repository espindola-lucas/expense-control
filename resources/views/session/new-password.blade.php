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
        <div class="h-screen flex">
            <div class="hidden sm-500:flex w-1/2 bg-gradient-to-tr from-blue-800 to-purple-700 justify-around items-center md:flex">
                <div>
                    <h1 class="text-white font-bold text-4xl font-sans hover:underline">Expense Control</h1>
                    <p class="text-white mt-1 max-w-[500px] overflow-hidden text-ellipsis line-clamp-3">
                        Es una web app que se encarga de registrar gastos, ingresos, y llevar un control detallado de tus finanzas personales.
                    </p>
                    <div class="mb-4">
                        <a target="_blank" href="https://github.com/espindola-lucas/expense-control">
                            <x-button>
                                Más
                            </x-button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex w-full sm-500:w-1/2 justify-center items-center bg-semi-white">
                <form method="POST" action="{{ route('password.update') }}" class="bg-white text-center mt-6 px-6 py-4 shadow-md sm:rounded-lg">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mt-4">
                        <x-form-input
                            type="password"
                            id="password"
                            name="password"
                            label="Nueva contraseña"
                        ></x-form-input>
                        @error('password_confirmation')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <x-form-input
                            type="password"
                            id="password"
                            name="password"
                            label="Confirmar nueva contraseña"
                        ></x-form-input>
                        @error('password_confirmation')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <x-button id="recovery-password">
                            Cambiar contraseña
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
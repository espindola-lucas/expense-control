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
                <form method="POST" action="{{ route('password.email') }}"" class="bg-white text-center mt-6 px-6 py-4 shadow-md sm:rounded-lg">
                    <p class="text-black w-80 mb-4">
                        Ingrese el correo electrónico asociado a su cuenta para cambiar su contraseña.
                    </p>
                    @csrf
                    <label for="email" class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                        <input
                            type="text"
                            id="email"
                            name="email"
                            required
                            autofocus
                            autocomplete="email"
                            class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900 px-2 py-2"
                            placeholder="email"
                        />
                        <span class="pointer-events-none absolute start-2.5 top-2 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
                            Email
                        </span>
                    </label>
                    
                    <div class="flex items-center justify-center mt-4">
                        <x-button id="recovery-password">
                            Enviar Mail
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
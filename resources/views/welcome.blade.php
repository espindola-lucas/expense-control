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
                        <a class="group relative inline-block overflow-hidden border border-white px-8 py-3 focus:outline-none focus:ring" target="_blank" href="https://github.com/espindola-lucas/expense-control">
                            <span class="absolute inset-y-0 left-0 w-[2px] bg-white transition-all group-hover:w-full group-active:bg-indigo-500"></span>
                            <span class="relative text-sm font-medium text-white transition-colors group-hover:text-indigo-600">
                                Más
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex w-full sm-500:w-1/2 justify-center items-center bg-semi-white">
                <form class="bg-white text-center mt-6 px-6 py-4 shadow-md sm:rounded-lg">
                    <h1 class="text-gray-800 font-bold text-2xl mb-1">Bienvenido!</h1>
                    <p class="text-sm font-normal text-gray-600 mb-7">¿Que quiere hacer?</p>
                    <div class="mb-4">
                        <a class="group relative inline-block overflow-hidden border border-indigo-600 px-8 py-3 focus:outline-none focus:ring" href="{{ route('session-login') }}">
                            <span class="absolute inset-y-0 left-0 w-[2px] bg-indigo-600 transition-all group-hover:w-full group-active:bg-indigo-500"></span>
                            <span class="relative text-sm font-medium text-indigo-600  transition-colors group-hover:text-white">
                                Iniciar Sesion
                            </span>
                        </a>
                    </div>
                    <div class="mb-4">
                        <a class="group relative inline-block overflow-hidden border border-indigo-600 px-10 py-3 focus:outline-none focus:ring" href="{{ route('session-register') }}">
                            <span class="absolute inset-y-0 left-0 w-[2px] bg-indigo-600 transition-all group-hover:w-full group-active:bg-indigo-500"></span>
                            <span class="relative text-sm font-medium text-indigo-600 transition-colors group-hover:text-white">
                                Registrarse
                            </span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

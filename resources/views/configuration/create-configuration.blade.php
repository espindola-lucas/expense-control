<!DOCTYPE html>
<html lang="en">
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
    <body>
        <div class="min-h-screen bg-page-custom">
            <x-header :user="$user"></x-header>
            <main class="container mx-auto p-4">
                <form method="POST" action="{{ route('configuration.store') }}">
                    @csrf
                    <div class="w-full md:w-5/6 mx-auto">
                        <div class="p-2 bg-white shadow-md rounded-md">
                            <!-- fecha inicio del periodo del mes -->
                            <div class="mt-4">
                                <label for="start_counting" class="block mb-2">
                                    Configure la fecha de inicio del periodo de conteo de gastos.
                                    <input id="start_counting" name="start_counting" type="date" value="{{ old('start_counting') }}"
                                        class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                                @error('start_counting')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label for="end_counting" class="block mb-2">
                                    Configure la fecha de fin del periodo de conteo de gastos.
                                    <input id="end_counting" name="end_counting" type="date" value="{{ old('end_counting') }}"
                                        class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                                @error('end_counting')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label for="available_money" class="block mb-2">
                                    Configure la cantidad de plata disponible para el mes, <br>
                                    sin puntos ni comas.
                                    <input id="available_money" name="available_money" type="number" value="{{ old('available_money') }}"
                                    class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                                @error('available_money')
                                <p class="text-red-800">
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                            <!-- <div>
                                <label class="label cursor-pointer">
                                    <input type="checkbox" id="info-checkbox" class="checkbox checkbox-primary rounded-md" />
                                    <span class="label-text">¿Sumar resto del mes anterior?</span>
                                </label>
                            </div>
                            <div id="info-content" class="hidden">
                                <p id="info-text" class="bg-green-300 inline-block py-1 px-4 mt-2 border rounded-md"></p>
                            </div> -->
                            <div class="mt-">
                                <label for="month_available_money" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
                                <select id="month_available_money" name="month_available_money" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 sm-500:w-1/2">
                                    <option selected disabled>Elegi el mes correspondiente al monto disponible</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month->value }}">{{ $month->name }}</option>
                                    @endforeach
                                </select>
                                @error('month_available_money')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label for="expense_percentage_limit" class="block mb-2">
                                    Coloque el porcentaje (%) del umbral.
                                    <input id="expense_percentage_limit" name="expense_percentage_limit" type="number" value="{{ old('expense_percentage_limit') }}"
                                    class="block w-full p-2 border border-gray-300 rounded sm-500:w-1/2"/>
                                </label>
                                @error('expense_percentage_limit')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="container mx-auto p-4">
                        <div class="w-full md:w-5/6 mx-auto flex justify-end">
                            <button type="submit" class="px-2 inline-flex text-xl leading-5 px-4 py-2 rounded-lg font-semibold bg-blue-100 text-blue-800">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </body>
<script src="{{ mix('/js/app.js') }}" defer></script>
<script>
    document.getElementById('info-checkbox').addEventListener('change', function () {
        const availableMoney = parseFloat(document.getElementById('available_money').value);
        if (this.checked) {
            fetch('/get-info')
                .then(response => response.json())
                .then(data => {
                    const total = parseFloat(availableMoney) + parseFloat(data.info);
                    document.getElementById('info-text').textContent = 'Se sumaran $ ' + data.info + ' al valor de la plata configurada ($ ' + availableMoney +'). Total: $ ' + total.toFixed(3);
                    document.getElementById('info-content').style.display = 'block';
                    let value = total.toFixed(3);
                    let splitValue = value.split('.');
                    let output = splitValue[0] + splitValue[1];
                    document.getElementById('available_money').value = output;
                })
                .catch(error => console.error('Error:', error));
        } else {
            document.getElementById('info-content').style.display = 'none';
        }
    });
</script>
</html>
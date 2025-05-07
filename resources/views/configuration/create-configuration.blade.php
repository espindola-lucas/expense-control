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
                                <x-form-input 
                                    type="date"
                                    id="start_counting"
                                    name="start_counting"
                                    value="{{ old('start_counting') }}"
                                    label="Configure la fecha de inicio del periodo de conteo de gastos." 
                                    required>
                                </x-form-input>
                                @error('start_counting')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <x-form-input 
                                    type="date"
                                    id="end_counting"
                                    name="end_counting"
                                    value="{{ old('end_counting') }}"
                                    label="Configure la fecha de fin del periodo de conteo de gastos." 
                                    required>
                                </x-form-input>
                                @error('end_counting')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <x-form-input 
                                    type="number"
                                    id="available_money"
                                    name="available_money"
                                    value="{{ old('available_money') }}"
                                    label="Configure la cantidad de plata disponible para el mes." 
                                    required>
                                </x-form-input>
                                @error('available_money')
                                <p class="text-red-800">
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
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
                                <x-form-input 
                                    type="number"
                                    id="expense_percentage_limit"
                                    name="expense_percentage_limit"
                                    value="{{ old('expense_percentage_limit') }}"
                                    label="Coloque el porcentaje (%) del umbral." 
                                    required>
                                </x-form-input>
                                @error('expense_percentage_limit')
                                <p class="text-red-800">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="container mx-auto p-4">
                        <div class="w-full md:w-5/6 mx-auto">
                            <section class="flex justify-between">
                                <a href="{{ route('configuration.index') }}" id="back-button">
                                    <x-button-add>
                                        Volver
                                    </x-button-add>
                                </a>
                                <button id="add-expense" type="submit" class="px-2 inline-flex text-xl leading-5 px-4 py-2 rounded-lg font-semibold bg-blue-100 text-blue-800">
                                    Agregar
                                </button>
                            </section>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </body>
    @vite('resources/js/app.js')
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
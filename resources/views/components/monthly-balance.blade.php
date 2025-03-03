<div class="bg-white shadow-md py-2 rounded-md p-2 lg:py-4 lg:flex lg:justify-between">
    <p class="text-center text-green-800">
        <span class="block lg:hidden">Disponible: $ {{ $availableMoney }}</span>
        <span class="hidden lg:block">Plata disponible: $ {{ $availableMoney }}</span>
    </p>
    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
    <p class="text-center text-red-800">
        <span class="block lg:hidden">Gastos: $ {{ $totalPrice }}</span>
        <span class="hidden lg:block">Total de gastos: ${{ $totalPrice }}</span>
    </p>
    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
    <p class="text-center text-blue-800">
        <span class="block lg:hidden">Resto: $ {{ $restMoney }}</span>
        <span class="hidden lg:block">Resto de plata disponible: $ {{ $restMoney }}</span>
    </p>
    <hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
    <p class="text-center text-orange-400">
        <span class="block lg:hidden">Cantidad: {{ $countSpent }}</span>
        <span class="hidden lg:block">Conteo de gastos: {{ $countSpent }}</span>
    </p>
</div>
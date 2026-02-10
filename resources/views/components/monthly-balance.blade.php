<section class="mt-6 mb-6 text-gray-400 body-font rounded-md border p-4">
    <div class="container">
        <div class="flex flex-wrap -m-4 text-center">
            <div class="p-4 sm:w-1/4 w-1/2">
                <h2 class="title-font font-medium text-2xl text-white">$ {{ $availableMoney }}</h2>
                <p class="leading-relaxed">Disponible</p>
            </div>
            <div class="p-4 sm:w-1/4 w-1/2">
                <h2 class="title-font font-medium text-2xl text-white">$ {{ $totalPrice }}</h2>
                <p class="leading-relaxed">Gastos</p>
            </div>
            <div class="p-4 sm:w-1/4 w-1/2">
                <h2 class="title-font font-medium text-2xl text-white">$ {{ $restMoney }}</h2>
                <p class="leading-relaxed">Resto</p>
            </div>
            <div class="p-4 sm:w-1/4 w-1/2">
                <h2 class="title-font font-medium text-2xl text-white">{{ $countSpent }}</h2>
                <p class="leading-relaxed">Cantidad</p>
            </div>
        </div>
    </div>
</section>
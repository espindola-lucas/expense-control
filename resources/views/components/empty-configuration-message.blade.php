<div class="w-11/12 mx-auto">
    <article class="overflow-hidden rounded-lg shadow-sm transition hover:shadow-lg">
        <div class="bg-white p-4 sm:p-6">
            <h1 class="text-xl text-center">Hola, <span class="font-semibold text-blue-700">{{ $user->name }}</span>!</h1>
            <p class="mt-2 mb-4 text-base text-center">
                Parece que <strong>aún no hiciste ninguna configuración.</strong> <br>
                Presioná el botón de abajo para empezar a configurar tu experiencia.
            </p>
            <a href="{{ route('configuration.index') }}" class="w-full max-w-xs flex flex-col items-center text-base text-white bg-blue-700 rounded-lg py-2 px-4 mx-auto">
                <span>Configuración</span>
            </a>
        </div>
    </article>
</div>
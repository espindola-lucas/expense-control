<nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-300 flex justify-around items-center h-14 shadow-inner z-50">
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-sm
        {{ Request::is('dashboard') ? 'text-white bg-blue-500 py-2 px-4 rounded-lg' : 'text-black hover:text-white hover:bg-blue-700 hover:py-2 hover:px-4 hover:rounded-lg' }}">
        <span>Inicio</span>
    </a>
    <a href="{{ route('configuration.index') }}" class="flex flex-col items-center text-sm
        {{ Request::is('configuration') ? 'text-white bg-blue-500 py-2 px-4 rounded-lg' : 'text-black hover:text-white hover:bg-blue-700 hover:py-2 hover:px-4 hover:rounded-lg' }}">
        <span>Configuración</span>
    </a>
    <form method="POST" action="{{ route('session-logout') }}" class="flex flex-col items-center text-sm text-back hover:bg-blue-700 hover:text-white hover:py-2 hover:px-4 hover:rounded-lg hover:text-white hover:bg-blue-700">
        @csrf
        <button type="submit">Salir</button>
    </form>
</nav>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-white">Panel Administrativo</h1>
    @foreach($users as $user)
    <div class="overflow-hidden bg-white shadow mb-4 sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Usuario</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <!-- ID -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $user->id }}</dd>
                </div>

                <!-- Nombre -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $user->name }}</dd>
                </div>

                <!-- Correo electrónico -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $user->email }}</dd>
                </div>

                <!-- Información del servidor -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">IP</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $server['SERVER_ADDR'] ?? 'desconocido' }}</dd>
                </div>

                <!-- HTTP_USER_AGENT -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">HTTP_USER_AGENT</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $server['HTTP_USER_AGENT'] ?? 'desconocido' }}</dd>
                </div>

                <!-- HTTP_HOST -->
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">HTTP_HOST</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $server['HTTP_HOST'] ?? 'desconocido' }}</dd>
                </div>

                <!-- REMOTE_ADDR -->
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">REMOTE_ADDR</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $server['REMOTE_ADDR'] ?? 'desconocido' }}</dd>
                </div>

                <!-- Acciones -->
                <div class="px-4 py-4 flex justify-center">
                    <form method="POST" action="{{ route('is_admin.destroy', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-2 py-2 bg-red-500 text-white rounded-lg">Eliminar</button>
                    </form>
                </div>
            </dl>
        </div>
    </div>
@endforeach
</div>
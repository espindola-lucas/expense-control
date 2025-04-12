<table class="min-w-full bg-card-custom divide-y-2 divide-gray-200 bg-white text-sm text-center mt-4 rounded-md hidden sm:block">
    <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-black">Inicio de Periodo</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-black">Fin de Periodo</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-black">Plata Disponible</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-black">Mes Correspondiente</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-black">Porcentaje Limite</th>
            <th class="px-4 py-2"></th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
        @foreach($configurations as $configuration)
        <tr>
            <td class="text-black">{{ $configuration->start_counting }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-black">{{ $configuration->end_counting }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-black">$ {{ $configuration->available_money }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-black">{{ $configuration->month_available_money }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-black">{{ $configuration->expense_percentage_limit }}%</td>
            <td class="whitespace-nowrap px-4 py-2">
                <a href="{{ route('configuration.show', $configuration->id) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                    Ver
                </a>
                @if($configuration->show_edit_button)
                    <a href="{{ route('configuration.edit', $configuration->id) }}" class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">
                        Editar
                    </a>
                @endif
                <form method="POST" action="{{ route('configuration.destroy', $configuration->id) }}" class="inline">
                    @method('DELETE')
                    @csrf
                    <button id="configuration-delete" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- responsive -->
<div class="bg-white rounded-md mt-6 sm:hidden block">
    <dl class="-my-3 divide-y divide-black text-sm">
        @foreach($configurations as $configuration)
        <div class="grid grid-cols-1 gap-1 py-3">
            <dt class="font-medium text-black text-center">Inicio de Periodo</dt>
            <dd class="text-black text-center sm:col-span-2">{{ $configuration->start_counting }}</dd>
        </div>
  
        <div class="grid grid-cols-1 gap-1 py-3">
            <dt class="font-medium text-black text-center">Fin de Periodo</dt>
            <dd class="text-black text-center sm:col-span-2">{{ $configuration->end_counting }}</dd>
        </div>
  
        <div class="grid grid-cols-1 gap-1 py-3">
            <dt class="font-medium text-black text-center">Plata Disponible</dt>
            <dd class="text-black text-center sm:col-span-2">$ {{ $configuration->available_money }}</dd>
        </div>
  
        <div class="grid grid-cols-1 gap-1 py-3">
            <dt class="font-medium text-black text-center">Mes Correspondiente</dt>
            <dd class="text-black text-center sm:col-span-2">{{ $configuration->month_available_money }}</dd>
        </div>
  
        <div class="grid grid-cols-1 gap-1 py-3">
            <dt class="font-medium text-black text-center">Porcentaje Limite</dt>
            <dd class="text-black text-center sm:col-span-2">{{ $configuration->expense_percentage_limit }}%</dd>
        </div>
        <div class="grid grid-cols-1 gap-1 py-3">
            <div class="flex space-x-2 justify-center">
                <a href="{{ route('configuration.show', $configuration->id) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                    Ver
                </a>
                @if($configuration->show_edit_button)
                    <a href="{{ route('configuration.edit', $configuration->id) }}" class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">
                        Editar
                    </a>
                @endif
                <form method="POST" action="{{ route('configuration.destroy', $configuration->id) }}" class="inline">
                    @method('DELETE')
                    @csrf
                    <button id="configuration-delete" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">Eliminar</button>
                </form>
            </div>
        </div>
        <hr>
        @endforeach
    </dl>
</div>

<table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm text-center mt-4">
    <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Inicio de Periodo</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Fin de Periodo</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Plata Disponible</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Mes Correspondiente</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Porcentaje Limite</th>
            <th class="px-4 py-2"></th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
        @foreach($configurations as $configuration)
        <tr>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $configuration->start_counting }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $configuration->end_counting }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $configuration->available_money }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $configuration->month_available_money }}</td>
            <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $configuration->expense_percentage_limit }}%</td>
            <td class="whitespace-nowrap px-4 py-2">
                <!-- este anchor es para el metodo show -->
                <a href="{{ route('configuration.show', $configuration->id) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                    Ver
                </a>
                <a href="{{ route('configuration.edit', $configuration->id) }}" class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">
                    Editar
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
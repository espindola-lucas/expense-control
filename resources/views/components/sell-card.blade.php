<div class="flex flex-col flex-wrap lg:grid lg:grid-cols-3 xl:grid xl:grid-cols-5">
    @foreach($sells as $sell)
        <div class="flex flex-col bg-card-custom my-2 mx-1 h-48 rounded-md py-4 px-6 xl:w-60">
            <h3 id="expense-title" class="text-center text-white font-bold text-xl text-gray-800 pb-2">{{ $sell->name }}</h3>
            <h3 class="text-base text-white font-semibold text-gray-900">$ {{ $sell->price }}</h3>
            <p class="text-sm text-gray-500 pb-3"></p>
            <div class="flex gap-2 text-sm text-gray-500 border-b pb-2">
                <p class="text-white">{{ __('last update') }}:</p>
                <p class="text-white">{{ $sell->expense_date }}</p>
            </div>
            <div class="flex justify-around items-center py-3">
                <div class="flex gap-2 text-gray-600 hover:scale-110 duration-200 hover:cursor-pointer hover:bg-green-700 hover:text-green-100 hover:px-2 hover:rounded-md">
                    <!-- <svg class="w-6 stroke-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg> -->
                    <a href="{{ route('sells.edit', $sell->id) }}">
                        <button class="font-semibold text-sm text-green-700 hover:text-green-100">Editar</button>
                    </a>
                </div>
                <div class="flex gap-2 text-gray-600 hover:scale-110 duration-200 hover:cursor-pointer hover:bg-red-700 hover:text-red-100 hover:px-2 hover:rounded-md">
                    <!-- <svg class="w-6 stroke-red-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> -->
                    <form method="POST" action="{{ route('sells.destroy', $sell->id) }}" class="inline">
                        @method('DELETE')
                        @csrf
                        <button id="expense-delete" class="font-semibold text-sm text-red-700 hover:text-red-100">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
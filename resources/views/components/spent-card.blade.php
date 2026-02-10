<div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5">
    @foreach($spents as $spent)
        

        <article class="group relative bg-white/3 rounded-md p-4 flex flex-col justify-between border">
            <div class="flex items-start justify-between">
                <div class="min-w-0 pr-2">
                    <h4 class="text-white font-semibold truncate">{{ $spent->name }}</h4>
                    <div class="text-xs text-white/70 mt-1">{{ $spent->expense_date }}</div>
                    @if(isset($spent->description) && $spent->description)
                        <p class="text-sm text-white/80 mt-2 line-clamp-2">{{ $spent->description }}</p>
                    @endif
                </div>

                <div class="text-right ml-3">
                    <div class="text-2xl font-bold text-white">
                        $ {{ ($spent->price) }}
                    </div>
                </div>
            </div>

            <div class="mt-3 flex items-center justify-between">
                <!-- Buttons: visible on mobile, hidden on md+ until hover -->
                <div class="flex items-center gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-150">
                    <a href="{{ route('spents.edit', $spent->id) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">Editar</a>

                    <form method="POST" action="{{ route('spents.destroy', $spent->id) }}" class="inline">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        </article>
    @endforeach
</div>
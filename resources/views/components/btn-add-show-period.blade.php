<div class="flex justify-between sm-500:mb-4">
    <a href="{{ route('spents.create') }}" id="add-expense">
        <x-button-add>
            Agregar
        </x-button-add>
    </a>

    <div class="hidden sm-500:block">
          <x-period-display :lastConfiguration="$lastConfiguration"/>
    </div>
</div>
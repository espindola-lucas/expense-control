<!-- resources/views/components/period-display.blade.php -->
@unless(!$lastConfiguration)
    @unless(is_null($lastConfiguration->start_counting) || is_null($lastConfiguration->end_counting))
        <div class="text-center text-white py-2 px-4">Periodo: {{ $lastConfiguration->start_counting }} - {{ $lastConfiguration->end_counting }}</div>
    @elseif(!is_null($lastConfiguration->start_counting))
        <div class="text-center text-white py-2 px-4">Periodo: {{ $lastConfiguration->start_counting }} - Sin corte</div>
    @else
        <div class="text-center text-white py-2 px-4">Periodo: Sin inicio - {{ $lastConfiguration->end_counting }}</div>
    @endunless
@endunless

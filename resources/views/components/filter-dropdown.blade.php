<!-- resources/views/components/filter-dropdown.blade.php -->
<div class="flex items-center space-x-4">
    <div class="mt-4">
        <label for="period" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
        <select id="period" name="period" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected disabled>Mes y Periodo Correspondiente</option>
            @foreach($allPeriods as $period)
                <option value="{{$period->month_available_money}}">
                    {{ $period->month_available_money }} : {{ $period->start_counting }} - {{ $period->end_counting }}
                </option>
            @endforeach
        </select>
    </div>
</div>
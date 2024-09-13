<!-- resources/views/components/filter-dropdown.blade.php -->
<div class="flex items-center space-x-4">
    <div class="mt-4">
        <label for="month" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
        <select id="month" name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected disabled>Elegi el mes</option>
            @foreach($months as $month)
            <option value="{{ $month->value }}" {{ $month->value == $selectedMonth ? 'selected' : '' }}>{{ $month->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mt-4">
        <label for="year" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
        <select id="year" name="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected disabled>Elegi el año</option>
            @foreach($years as $year)
            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>
</div>
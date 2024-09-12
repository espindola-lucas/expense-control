<section class="items-center px-5 py-2 lg:px-20">
    <div {{ $attributes->merge(['class' => 'w-full text-green-700 border rounded-lg shadow-xl']) }}>
        <div class="px-6 py-4 mx-auto">
            <p class="text-center font-semibold tracking-wide">
                {{ $message }}
            </p>
        </div>
    </div>
</section>
<div {{ $attributes->merge(['class' => 'fixed inset-0 bg-white bg-opacity-80 z-30']) }}>
    <div class="h-full p-4 flex flex-wrap items-center justify-center">
        <div class="w-full max-h-full max-w-screen-xl overflow-y-auto p-8 bg-white rounded-2xl shadow-lg">
            {{ $header }}

            {{ $slot }}

            {{ $actions }}
        </div>
    </div>
</div>

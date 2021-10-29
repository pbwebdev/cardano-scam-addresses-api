<template>
    <div class="md:col-span-1">
        <h3 class="text-lg font-medium text-gray-900">
            <slot name="title"></slot>
        </h3>

        <p class="mt-1 text-sm text-gray-600">
            <slot name="description"></slot>
        </p>

        <div class="pt-4 italic">
            <slot name="aside"></slot>
        </div>
    </div>
</template>

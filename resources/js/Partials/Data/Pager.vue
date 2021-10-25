<template>
    <select
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        @change="$emit('reloadItems', $event.target.value)"
    >
        <option v-for="(option, index) in options" :key="index" :value="option" :selected="option === defaultPager">
            {{ option }}
        </option>
    </select>
</template>

<script>
    import { defineComponent } from "vue"
    import { correctLink, defaultPager, getPageNumber } from "@/utilities/pageHelpers";

    export default defineComponent({
        emits: [
            'reloadItems',
        ],

        setup() {
            const [size, start, step] = [10, 10, 10];
            const options = Array(size).fill().map((_, i) => start + i * step);

            return {
                options,
                defaultPager,
                getPageNumber,
                correctLink,
            }
        },
    })
</script>

<template>
    <nav v-if="pages.length > 0" class="my-10">
        <ol class="flex flex-wrap items-center justify-center">
            <li v-for="page in pages" :key="page.label" class="p-4">
                <a :href="correctLink(page.url)"
                   class="text-[#6875F5]"
                   :class="{
                            'pointer-events-none': !page.url || page.active,
                            'opacity-80': !page.url || page.active
                        }"
                   @click.prevent="$emit('loadItems', page.url)"
                   v-html="page.label"
                ></a>
            </li>
        </ol>
    </nav>
</template>

<script>
    import { defineComponent } from "vue"

    export default defineComponent({
        props: {
            pages: {
                type: Array,
                required: true,
            }
        },

        emits: [
            'loadItems',
        ],

        setup() {
            const correctLink = url => {
                return url ? url.replace('/api', '') : `#`;
            };

            return {
                correctLink,
            }
        },
    })
</script>

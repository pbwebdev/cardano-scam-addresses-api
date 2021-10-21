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
                   @click.prevent="$emit('loadItems', getPageNumber(page.url))"
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

        setup(_, {emit}) {
            const getPageNumber = url => {
                const apiUrl = new URL(url);

                return apiUrl.searchParams.get('page');
            }

            const correctLink = url => {
                if (!url) {
                    return '#';
                }

                const windowUrl = new URL(window.location);

                windowUrl.searchParams.set('page', getPageNumber(url))

                return windowUrl.toString();
            };

            return {
                getPageNumber,
                correctLink,
            }
        },
    })
</script>

<template>
    <app-layout title="Submissions">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Submissions
                </h1>

                <resource-pager @reloadItems="loadSize"></resource-pager>

                <Link :href="route(`submissions.create`)"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    Add
                </Link>
            </div>
        </template>

        <div v-if="state.items.length > 0"
             class="w-full sm:max-w-4xl mx-auto my-6 p-6 bg-white shadow-md break-words sm:rounded-lg">
            <ul class="list-decimal ml-10">
                <li v-for="item in state.items" :key="item.id">
                    <Link :href="route(`submissions.show`, item.id)"
                          class="text-[#6875F5]"
                    >
                        {{ item.transaction }}
                    </Link>
                </li>
            </ul>
        </div>

        <resource-pagination
            :pages="state.pages"
            @loadItems="loadData"
        />
    </app-layout>
</template>

<script>
    import { defineComponent, onMounted } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import ResourcePagination from '@/Partials/Data/Pagination'
    import ResourcePager from '@/Partials/Data/Pager'
    import { Link } from '@inertiajs/inertia-vue3';
    import composableResource from "@/utilities/composableResource";

    export default defineComponent({
        components: {
            AppLayout,
            ResourcePagination,
            ResourcePager,
            Link,
        },

        setup() {
            const {
                state,
                loadData,
            } = composableResource('submissions')

            const loadSize = per_page => loadData(1, per_page);

            onMounted(() => {
                loadData();
            })

            return {
                state,
                loadData,
                loadSize,
            }
        },
    })
</script>

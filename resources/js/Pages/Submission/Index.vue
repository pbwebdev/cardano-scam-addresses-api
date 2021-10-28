<template>
    <app-layout title="Submissions">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Submissions
                </h1>

                <resource-pager @reloadItems="loadSize"></resource-pager>

                <Link :href="route(`submit`)"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    Add
                </Link>
            </div>
        </template>

        <resource-data ref="resourceData" routeBaseName="submissions" headingTitle="Submission" noModal
                       customKey="transaction" />
    </app-layout>
</template>

<script>
    import { defineComponent, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import ResourceData from '@/Partials/Data'
    import ResourcePager from '@/Partials/Data/Pager'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            AppLayout,
            ResourceData,
            ResourcePager,
            Link,
        },

        setup() {
            const resourceData = ref(null);
            const addItem = () => resourceData.value.manageAction(0);
            const loadSize = per_page => resourceData.value.loadData(1, per_page);

            return {
                addItem,
                loadSize,
                resourceData,
            }
        },
    })
</script>

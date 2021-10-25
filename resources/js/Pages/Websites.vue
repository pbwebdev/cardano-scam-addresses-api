<template>
    <app-layout title="Websites">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Websites
                </h1>

                <resource-pager @reloadItems="loadSize"></resource-pager>

                <jet-button
                    @click="addItem"
                    v-if="$page.props.isAdmin"
                >
                    Add
                </jet-button>
            </div>
        </template>

        <resource-data ref="resourceData" routeBaseName="websites" headingTitle="Website" />
    </app-layout>
</template>

<script>
    import { defineComponent, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import ResourceData from '@/Partials/Data'
    import ResourcePager from '@/Partials/Data/Pager'
    import JetButton from "@/Jetstream/Button";

    export default defineComponent({
        components: {
            AppLayout,
            ResourceData,
            ResourcePager,
            JetButton,
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

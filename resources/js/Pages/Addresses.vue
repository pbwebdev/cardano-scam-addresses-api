<template>
    <app-layout title="Addresses">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Addresses
                </h1>

                <jet-button
                    @click="addItem"
                    v-if="$page.props.isAdmin"
                >
                    Add
                </jet-button>
            </div>
        </template>

        <resource-data ref="resourceData" routeBaseName="addresses" headingTitle="Address" />
    </app-layout>
</template>

<script>
    import { defineComponent, ref } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import ResourceData from '@/Partials/Data'
    import JetButton from "@/Jetstream/Button";

    export default defineComponent({
        components: {
            AppLayout,
            ResourceData,
            JetButton,
        },

        setup() {
            const resourceData = ref(null);
            const addItem = () => resourceData.value.manageAction(0);

            return {
                addItem,
                resourceData,
            }
        },
    })
</script>

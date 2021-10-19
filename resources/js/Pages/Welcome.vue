<template>
    <app-layout title="Welcome" :can-login="canLogin" :can-register="canRegister">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Welcome
            </h2>
        </template>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'

    export default defineComponent({
        components: {
            AppLayout,
        },

        props: {
            canLogin: Boolean,
            canRegister: Boolean,
        },
    })
</script>

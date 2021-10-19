<template>
    <div>
        <Head :title="title" />

        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Page Navigation -->
            <navigation />

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetBanner from '@/Jetstream/Banner.vue'
    import Navigation from '@/Partials/Navigation'
    import { Head } from "@inertiajs/inertia-vue3";

    export default defineComponent({
        props: {
            title: String,
        },

        components: {
            Head,
            JetBanner,
            Navigation,
        },
    })
</script>

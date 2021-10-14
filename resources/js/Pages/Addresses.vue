<template>
    <Head title="Addresses" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <jet-authentication-card-logo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
                        Addresses
                    </h1>

                    <div v-if="addresses.length > 0" class="mt-4 overflow-x-auto">
                        <ul class="list-decimal ml-8">
                            <li v-for="data in addresses" :key="data.id">{{ data.address }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'

    export default defineComponent({
        components: {
            Head,
            Link,
            JetAuthenticationCardLogo,
        },

        data() {
            return {
                addresses: [],
            }
        },

        mounted() {
            const response = axios.get(route('addresses.index'));

            response.then(data => {
                this.addresses = data?.data?.data || [];
            });
        }
    })
</script>

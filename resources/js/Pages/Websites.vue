<template>
    <Head title="Websites" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <jet-authentication-card-logo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
                            Websites
                        </h1>

                        <jet-button
                            @click="createWebsite.modal = true"
                            v-if="isAdmin" :class="{ 'opacity-25': createWebsite.processing }"
                            :disabled="createWebsite.processing">
                            Add
                        </jet-button>
                    </div>

                    <div v-if="websites.length > 0" class="mt-4 pt-4 border-t border-gray-200 overflow-x-auto">
                        <ul class="list-decimal ml-10">
                            <li v-for="data in websites" :key="data.id">
                                <a href="#"
                                   class="text-[#6875F5]"
                                   @click="manageWebsiteAction(data.id)"
                                   v-if="isAdmin"
                                >
                                    {{ data.address }}
                                </a>
                                <span v-else>{{ data.address }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <jet-dialog-modal :show="createWebsite.modal" @close="createWebsite.modal = false">
        <template #title>
            Add New Website
        </template>

        <template #content>
            <jet-input type="text" class="w-full" v-model="createWebsite.address"
                       required autofocus />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-secondary-button @click="createWebsite.modal = false">
                    Cancel
                </jet-secondary-button>

                <jet-button @click="createWebsiteAction"
                            :class="{ 'opacity-25': createWebsite.processing }"
                            :disabled="createWebsite.processing">
                    Save
                </jet-button>
            </div>
        </template>
    </jet-dialog-modal>

    <jet-dialog-modal :show="editWebsite.modal" @close="editWebsite.modal = false">
        <template #title>
            Edit Website
        </template>

        <template #content>
            <jet-input type="text" class="w-full" v-model="editWebsite.address"
                       required autofocus />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-danger-button @click="removeWebsiteAction"
                                   :class="{ 'opacity-25': editWebsite.processing }" :disabled="editWebsite.processing">
                    Delete
                </jet-danger-button>

                <jet-secondary-button @click="editWebsite.modal = false">
                    Cancel
                </jet-secondary-button>

                <jet-button @click="editWebsiteAction"
                            :class="{ 'opacity-25': editWebsite.processing }"
                            :disabled="editWebsite.processing">
                    Update
                </jet-button>
            </div>
        </template>
    </jet-dialog-modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'

    export default defineComponent({
        components: {
            Head,
            Link,
            JetAuthenticationCardLogo,
            JetDialogModal,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetInput,
            JetLabel,
        },

        props: ['isAdmin'],

        data() {
            return {
                websites: [],
                createWebsite: {
                    modal: false,
                    address: null,
                    processing: false,
                },
                editWebsite: {
                    id: null,
                    modal: false,
                    address: null,
                    processing: false,
                },
            }
        },

        mounted() {
            const response = axios.get(route('websites.index'));

            response.then(data => {
                this.websites = data?.data?.data || [];
            });
        },

        methods: {
            createWebsiteAction() {
                this.createWebsite.processing = true;

                const response = axios.post(route('websites.store'), {
                    address: this.createWebsite.address,
                });

                response.then(data => {
                    this.createWebsite = {
                        modal: false,
                        address: null,
                        processing: false,
                    }

                    const website = data?.data?.data ?? false;

                    if (website) {
                        this.websites.push(website);
                    }

                    return data;
                });
            },

            manageWebsiteAction(id) {
                this.editWebsite.id = id;
                this.editWebsite.modal = true;

                const index = this.websites.findIndex(object => object.id === id);

                this.editWebsite.address = this.websites[index].address;
            },

            editWebsiteAction() {
                this.editWebsite.processing = true;

                const response = axios.patch(route('websites.update', this.editWebsite.id), {
                    address: this.editWebsite.address,
                });

                response.then(data => {
                    this.editWebsite = {
                        id: null,
                        modal: false,
                        address: null,
                        processing: false,
                    }

                    const website = data?.data?.data ?? false;

                    if (website) {
                        const index = this.websites.findIndex(object => object.id === website.id);

                        this.websites[index] = website;
                    }

                    return data;
                });
            },

            removeWebsiteAction() {
                this.editWebsite.processing = true;

                const response = axios.delete(route('websites.destroy', this.editWebsite.id));

                response.then(data => {
                    const index = this.websites.findIndex(object => object.id === this.editWebsite.id);

                    this.editWebsite = {
                        id: null,
                        modal: false,
                        address: null,
                        processing: false,
                    }

                    this.websites.splice(index, 1);

                    return data;
                });
            }
        }
    })
</script>

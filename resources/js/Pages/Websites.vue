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
                            @click="manageWebsiteAction(null)"
                            v-if="isAdmin" :class="{ 'opacity-25': formProcessing }"
                            :disabled="formProcessing">
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

                <nav v-if="pages.length > 0" class="mt-6">
                    <ol class="flex flex-wrap">
                        <li v-for="page in pages" :key="page.label" class="p-4">
                            <a :href="correctLink(page.url)"
                               class="text-[#6875F5]"
                               :class="{
                                    'pointer-events-none': !page.url || page.active,
                                    'opacity-80': !page.url || page.active
                                }"
                               v-on:click.prevent="loadWebsites(page.url)"
                               v-html="page.label"
                            ></a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <jet-dialog-modal :show="modalActive" @close="modalActive = false">
        <template #title>
            {{ modalTitle }}
        </template>

        <template #content>
            <jet-input type="text" class="w-full" v-model="fieldValue"
                       required autofocus />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-danger-button v-if="managedId" @click="removeWebsiteAction"
                                   :class="{ 'opacity-25': formProcessing }" :disabled="formProcessing">
                    Delete
                </jet-danger-button>

                <jet-secondary-button @click="modalActive = false">
                    Cancel
                </jet-secondary-button>

                <jet-button v-if="managedId" @click="editWebsiteAction"
                            :class="{ 'opacity-25': formProcessing }"
                            :disabled="formProcessing">
                    Update
                </jet-button>

                <jet-button v-else @click="createWebsiteAction"
                            :class="{ 'opacity-25': formProcessing }"
                            :disabled="formProcessing">
                    Save
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
                pages: [],
                managedId: null,
                modalActive: false,
                fieldValue: null,
                formProcessing: false,
            }
        },

        computed: {
            modalTitle: function () {
                return `${this.managedId ? 'Edit' : 'Add New'} Website`;
            }
        },

        mounted() {
            this.loadWebsites(route('websites.index', {
                page: new URLSearchParams(window.location.search).get('page')
            }));
        },

        methods: {
            correctLink: function (url) {
                return url ? url.replace('/api', '') : `#`;
            },

            resetData() {
                this.managedId = null;
                this.modalActive = false;
                this.fieldValue = null;
                this.formProcessing = false;
            },

            loadWebsites(url) {
                const response = axios.get(url);

                response.then(data => {
                    this.websites = data?.data?.data || [];
                    this.pages = data?.data?.meta.links || [];
                });
            },

            createWebsiteAction() {
                this.formProcessing = true;

                const response = axios.post(route('websites.store'), {
                    address: this.fieldValue,
                });

                response.then(data => {
                    this.resetData();

                    const website = data?.data?.data ?? false;

                    if (website) {
                        this.websites.push(website);
                    }

                    return data;
                });
            },

            manageWebsiteAction(id) {
                this.managedId = id;
                this.modalActive = true;
                let changeValue = null;

                if (id) {
                    const index = this.websites.findIndex(object => object.id === id);
                    changeValue = this.websites[index].address;
                }

                this.fieldValue = changeValue;
            },

            editWebsiteAction() {
                this.formProcessing = true;

                const response = axios.patch(route('websites.update', this.managedId), {
                    address: this.fieldValue,
                });

                response.then(data => {
                    this.resetData();

                    const website = data?.data?.data ?? false;

                    if (website) {
                        const index = this.websites.findIndex(object => object.id === website.id);

                        this.websites[index] = website;
                    }

                    return data;
                });
            },

            removeWebsiteAction() {
                this.formProcessing = true;

                const response = axios.delete(route('websites.destroy', this.managedId));

                response.then(data => {
                    const index = this.websites.findIndex(object => object.id === this.managedId);

                    this.resetData();
                    this.websites.splice(index, 1);

                    return data;
                });
            }
        }
    })
</script>

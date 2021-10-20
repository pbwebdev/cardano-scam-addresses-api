<template>
    <app-layout title="Addresses">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    Addresses
                </h1>

                <jet-button
                    @click="manageAddressAction(null)"
                    v-if="isAdmin" :class="{ 'opacity-25': formProcessing }"
                    :disabled="formProcessing">
                    Add
                </jet-button>
            </div>
        </template>

        <div v-if="addresses.length > 0"
             class="w-full sm:max-w-4xl mx-auto my-6 p-6 bg-white shadow-md break-words sm:rounded-lg">
            <ul class="list-decimal ml-10">
                <li v-for="data in addresses" :key="data.id">
                    <a href="#"
                       class="text-[#6875F5]"
                       @click="manageAddressAction(data.id)"
                       v-if="isAdmin"
                    >
                        {{ data.address }}
                    </a>
                    <span v-else>{{ data.address }}</span>
                </li>
            </ul>
        </div>

        <nav v-if="pages.length > 0" class="my-10">
            <ol class="flex flex-wrap items-center justify-center">
                <li v-for="page in pages" :key="page.label" class="p-4">
                    <a :href="correctLink(page.url)"
                       class="text-[#6875F5]"
                       :class="{
                            'pointer-events-none': !page.url || page.active,
                            'opacity-80': !page.url || page.active
                        }"
                       v-on:click.prevent="loadAddresses(page.url)"
                       v-html="page.label"
                    ></a>
                </li>
            </ol>
        </nav>
    </app-layout>

    <jet-dialog-modal :show="modalActive" @close="modalActive = false">
        <template #title>
            {{ modalTitle }}
        </template>

        <template #content>
            <p v-if="formMessage" class="mb-2 font-medium text-red-600">{{ formMessage }}</p>
            <jet-input type="text" class="w-full" v-model="fieldValue"
                       required autofocus />
            <jet-input-error :message="fieldError" class="mt-2" />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-danger-button v-if="managedId" @click="removeAddressAction"
                                   :class="{ 'opacity-25': formProcessing }" :disabled="formProcessing">
                    Delete
                </jet-danger-button>

                <jet-secondary-button @click="modalActive = false">
                    Cancel
                </jet-secondary-button>

                <jet-button v-if="managedId" @click="editAddressAction"
                            :class="{ 'opacity-25': formProcessing }"
                            :disabled="formProcessing">
                    Update
                </jet-button>

                <jet-button v-else @click="createAddressAction"
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
    import AppLayout from '@/Layouts/AppLayout.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetFormErrors from '@/Jetstream/InputError.vue'

    export default defineComponent({
        components: {
            AppLayout,
            JetDialogModal,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetInput,
            JetLabel,
            JetInputError,
            JetFormErrors,
        },

        props: ['isAdmin'],

        data() {
            return {
                addresses: [],
                pages: [],
                managedId: null,
                modalActive: false,
                fieldValue: null,
                formProcessing: false,
                fieldError: '',
                formMessage: '',
            }
        },

        computed: {
            modalTitle: function () {
                return `${this.managedId ? 'Edit' : 'Add New'} Address`;
            }
        },

        mounted() {
            this.loadAddresses(route('addresses.index', {
                page: new URLSearchParams(window.location.search).get('page')
            }));
        },

        methods: {
            correctLink: function (url) {
                return url ? url.replace('/api', '') : `#`;
            },

            resetData(manageId = null, openModal = false) {
                this.managedId = manageId;
                this.modalActive = openModal;
                this.fieldValue = null;
                this.formMessage = '';
                this.fieldError = '';
            },

            loadAddresses(url) {
                const response = axios.get(url);

                response.then(data => {
                    this.addresses = data?.data?.data || [];
                    this.pages = data?.data?.pagination || [];
                });
            },

            createAddressAction() {
                this.formProcessing = true;

                const response = axios.post(route('addresses.store'), {
                    address: this.fieldValue,
                });

                response.then(data => {
                    this.resetData();

                    const address = data?.data?.data ?? false;

                    if (address) {
                        this.addresses.push(address);
                    }

                    return data;
                }).catch(error => {
                    this.formMessage = error?.response?.data?.message || '';
                    this.fieldError = error?.response?.data?.errors?.address[0] || '';
                }).finally(() => {
                    this.formProcessing = false;
                });
            },

            manageAddressAction(id) {
                this.resetData(id, true)

                if (id) {
                    const index = this.addresses.findIndex(object => object.id === id);
                    this.fieldValue = this.addresses[index].address;
                }
            },

            editAddressAction() {
                this.formProcessing = true;

                const response = axios.patch(route('addresses.update', this.managedId), {
                    address: this.fieldValue,
                });

                response.then(data => {
                    this.resetData();

                    const address = data?.data?.data ?? false;

                    if (address) {
                        const index = this.addresses.findIndex(object => object.id === address.id);

                        this.addresses[index] = address;
                    }

                    return data;
                }).catch(error => {
                    this.formMessage = error?.response?.data?.message || '';
                    this.fieldError = error?.response?.data?.errors?.address[0] || '';
                }).finally(() => {
                    this.formProcessing = false;
                });
            },

            removeAddressAction() {
                this.formProcessing = true;

                const response = axios.delete(route('addresses.destroy', this.managedId));

                response.then(data => {
                    const index = this.addresses.findIndex(object => object.id === this.managedId);

                    this.resetData();
                    this.addresses.splice(index, 1);

                    return data;
                }).catch(error => {
                    this.formMessage = error?.response?.data?.message || '';
                    this.fieldError = error?.response?.data?.errors?.address[0] || '';
                }).finally(() => {
                    this.formProcessing = false;
                });
            }
        }
    })
</script>

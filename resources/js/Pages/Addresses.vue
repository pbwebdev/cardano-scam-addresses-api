<template>
    <Head title="Addresses" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <jet-authentication-card-logo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
                            Addresses
                        </h1>

                        <jet-button
                            @click="createAddress.modal = true"
                            v-if="isAdmin" :class="{ 'opacity-25': createAddress.processing }"
                            :disabled="createAddress.processing">
                            Add
                        </jet-button>
                    </div>

                    <div v-if="addresses.length > 0" class="mt-4 pt-4 border-t border-gray-200 overflow-x-auto">
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
                </div>
            </div>
        </div>
    </div>

    <jet-dialog-modal :show="createAddress.modal" @close="createAddress.modal = false">
        <template #title>
            Add New Address
        </template>

        <template #content>
            <jet-input type="text" class="w-full" v-model="createAddress.address"
                       required autofocus />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-secondary-button @click="createAddress.modal = false">
                    Cancel
                </jet-secondary-button>

                <jet-button @click="createAddressAction"
                            :class="{ 'opacity-25': createAddress.processing }"
                            :disabled="createAddress.processing">
                    Save
                </jet-button>
            </div>
        </template>
    </jet-dialog-modal>

    <jet-dialog-modal :show="editAddress.modal" @close="editAddress.modal = false">
        <template #title>
            Edit Address
        </template>

        <template #content>
            <jet-input type="text" class="w-full" v-model="editAddress.address"
                       required autofocus />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-danger-button @click="removeAddressAction"
                                   :class="{ 'opacity-25': editAddress.processing }" :disabled="editAddress.processing">
                    Delete
                </jet-danger-button>

                <jet-secondary-button @click="editAddress.modal = false">
                    Cancel
                </jet-secondary-button>

                <jet-button @click="editAddressAction"
                            :class="{ 'opacity-25': editAddress.processing }"
                            :disabled="editAddress.processing">
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
                addresses: [],
                createAddress: {
                    modal: false,
                    address: null,
                    processing: false,
                },
                editAddress: {
                    id: null,
                    modal: false,
                    address: null,
                    processing: false,
                },
            }
        },

        mounted() {
            const response = axios.get(route('addresses.index'));

            response.then(data => {
                this.addresses = data?.data?.data || [];
            });
        },

        methods: {
            createAddressAction() {
                this.createAddress.processing = true;

                const response = axios.post(route('addresses.store'), {
                    address: this.createAddress.address,
                });

                response.then(data => {
                    this.createAddress = {
                        modal: false,
                        address: null,
                        processing: false,
                    }

                    const address = data?.data?.data ?? false;

                    if (address) {
                        this.addresses.push(address);
                    }

                    return data;
                });
            },

            manageAddressAction(id) {
                this.editAddress.id = id;
                this.editAddress.modal = true;

                const index = this.addresses.findIndex(object => object.id === id);

                this.editAddress.address = this.addresses[index].address;
            },

            editAddressAction() {
                this.editAddress.processing = true;

                const response = axios.patch(route('addresses.update', this.editAddress.id), {
                    address: this.editAddress.address,
                });

                response.then(data => {
                    this.editAddress = {
                        id: null,
                        modal: false,
                        address: null,
                        processing: false,
                    }

                    const address = data?.data?.data ?? false;

                    if (address) {
                        const index = this.addresses.findIndex(object => object.id === address.id);

                        this.addresses[index] = address;
                    }

                    return data;
                });
            },

            removeAddressAction() {
                this.editAddress.processing = true;

                const response = axios.delete(route('addresses.destroy', this.editAddress.id));

                response.then(data => {
                    const index = this.addresses.findIndex(object => object.id === this.editAddress.id);

                    this.editAddress = {
                        id: null,
                        modal: false,
                        address: null,
                        processing: false,
                    }

                    this.addresses.splice(index, 1);

                    return data;
                });
            }
        }
    })
</script>

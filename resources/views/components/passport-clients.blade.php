<div x-data="clientData()" x-init="prepareComponent()" class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex justify-between items-center">
            <h2 class="uppercase text-3xl font-bold">
                OAuth Clients
            </h2>

            <a href="#" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-blue-400" tabindex="-1" @click.prevent="modal.create = true">
                Create New Client
            </a>
        </div>

        <div class="mt-4">
            <!-- No Tokens Notice -->
            <template x-if="clients.length === 0">
                <p>
                    You have not created any OAuth client.
                </p>
            </template>

            <!-- Authorized Tokens -->
            <template x-if="clients.length > 0">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="p-4 border border-blue-400 text-left">Client ID</th>
                            <th class="p-4 border border-blue-400 text-left">Name</th>
                            <th class="p-4 border border-blue-400">Secret</th>
                            <th class="p-4 border border-blue-400 w-64">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="client in clients">
                            <tr>
                                <!-- Client ID -->
                                <td class="p-4" x-text="client.id"></td>

                                <!-- Name -->
                                <td class="p-4" x-text="client.name"></td>

                                <!-- Secret -->
                                <td class="p-4" x-text="client.secret ? client.secret : '&mdash;'"></td>

                                <td class="-mx-4 text-center">
                                    <!-- Edit Button -->
                                    <div class="inline-block p-4">
                                        <a href="#" class="underline text-blue-400" @click.prevent="edit(client)">
                                            Edit
                                        </a>
                                    </div>

                                    <!-- Revoke Button -->
                                    <div class="inline-block p-4">
                                        <a href="#" class="underline text-red-400" @click.prevent="remove(client)">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>
        </div>
    </div>

    <!-- Create Client Modal -->
    <x-modal id="modal-create-client" x-show="modal.create">
        <x-slot name="header">
            <div class="flex flex-wrap items-center justify-between -mx-4">
                <div class="px-4">
                    <h4 class="uppercase text-3xl font-bold">Create Client</h4>
                </div>

                <div class="px-4">
                    <button type="button" aria-hidden="true" @click="modal.create = false">&times;</button>
                </div>
            </div>
        </x-slot>

        <div class="my-4">
            <!-- Form Errors -->
            <template x-if="form.create.errors.length > 0">
                <div class="mb-4 text-red-400">
                    <p class="mb-2"><strong>Whoops!</strong> Something went wrong!</p>
                    <ul class="pl-4 list-disc">
                        <template x-for="error in form.create.errors">
                            <li x-text="error"></li>
                        </template>
                    </ul>
                </div>
            </template>

            <!-- Create Client Form -->
            <form role="form" @submit.prevent="store()">
                <!-- Name -->
                <div class="md:flex my-2 flex-wrap justify-between">
                    <label for="create-client-name">Name</label>

                    <input id="create-client-name" type="text" class="w-full my-2 py-2 px-4 placeholder-gray-400" name="name" x-model="form.create.name">

                    <span class="text-gray-400">
                        Something your users will recognize and trust.
                    </span>
                </div>

                <!-- Redirect URL -->
                <div class="md:flex my-2 flex-wrap justify-between">
                    <label for="create-client-redirect">Redirect URL</label>

                    <input id="create-client-redirect" type="url" class="w-full my-2 py-2 px-4 placeholder-gray-400" name="redirect" x-model="form.create.redirect">

                    <span class="text-gray-400">
                        Your application's authorization callback URL.
                    </span>
                </div>

                <!-- Confidential -->
                <div class="md:flex my-2 flex-wrap justify-between">
                    <label for="create-client-confidential">Confidential</label>

                    <div class="w-full my-2">
                        <input id="create-client-confidential" type="checkbox" class="mr-2" name="confidential" x-model="form.create.confidential">

                        <span class="text-gray-400">
                            Require the client to authenticate with a secret. Confidential clients can hold credentials in a secure way without exposing them to unauthorized parties. Public applications, such as native desktop or JavaScript SPA applications, are unable to hold secrets securely.
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <x-slot name="actions">
            <div class="flex flex-wrap justify-end -mx-4">
                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-red-400" @click="modal.create = false">Close</button>
                </div>

                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-blue-400" @click="store()">
                        Create
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <!-- Edit Client Modal -->
    <x-modal id="modal-edit-client" x-show="modal.edit">
        <x-slot name="header">
            <div class="flex flex-wrap items-center justify-between -mx-4">
                <div class="px-4">
                    <h4 class="uppercase text-3xl font-bold">Edit Client</h4>
                </div>

                <div class="px-4">
                    <button type="button" aria-hidden="true" @click="modal.edit = false">&times;</button>
                </div>
            </div>
        </x-slot>

        <div class="my-4">
            <!-- Form Errors -->
            <template x-if="form.edit.errors.length > 0">
                <div class="mb-4 text-red-400">
                    <p class="mb-2"><strong>Whoops!</strong> Something went wrong!</p>
                    <ul class="pl-4 list-disc">
                        <template x-for="error in form.edit.errors">
                            <li x-text="error"></li>
                        </template>
                    </ul>
                </div>
            </template>

            <!-- Edit Client Form -->
            <form role="form" @submit.prevent="update()">
                <!-- Name -->
                <div class="md:flex my-2 flex-wrap justify-between">
                    <label for="edit-client-name">Name</label>

                    <input id="edit-client-name" type="text" class="w-full my-2 py-2 px-4 placeholder-gray-400" name="name" x-model="form.edit.name">

                    <span class="text-gray-400">
                        Something your users will recognize and trust.
                    </span>
                </div>

                <!-- Redirect URL -->
                <div class="md:flex my-2 flex-wrap justify-between">
                    <label for="edit-client-redirect">Redirect URL</label>

                    <input id="edit-client-redirect" type="url" class="w-full my-2 py-2 px-4 placeholder-gray-400" name="redirect" x-model="form.edit.redirect">

                    <span class="text-gray-400">
                        Your application's authorization callback URL.
                    </span>
                </div>
            </form>
        </div>

        <x-slot name="actions">
            <div class="flex flex-wrap justify-end -mx-4">
                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-red-400" @click="modal.edit = false">Close</button>
                </div>

                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-blue-400" @click="update()">
                        Save
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>

<script>
    function clientData() {
        return {
            clients: [],

            modal: {
                create: false,
                edit: false,
            },

            form: {
                create: {
                    errors: [],
                    name: '',
                    redirect: '',
                    confidential: true
                },

                edit: {
                    errors: [],
                    id: '',
                    name: '',
                    redirect: '',
                }
            },

            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getClients();
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                    .then(response => {
                        this.clients = response.data;
                    });
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post',
                    '/oauth/clients/',
                    this.form.create
                );
                this.modal.create = false;
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put',
                    '/oauth/clients/' + this.form.edit.id,
                    this.form.edit
                );
                this.modal.edit = false;
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form) {
                form.errors = [];

                axios[method](uri, form)
                    .then(() => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.form.edit.id = client.id;
                this.form.edit.name = client.name;
                this.form.edit.redirect = client.redirect;

                this.modal.edit = true;
            },

            /**
             * Remove the given client.
             */
            remove(client) {
                axios.delete('/oauth/clients/' + client.id)
                    .then(() => {
                        this.getClients();
                    });
            },
        }
    }
</script>

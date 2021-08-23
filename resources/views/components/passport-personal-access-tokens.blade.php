<div x-data="tokenData()" x-init="prepareComponent()" class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex justify-between items-center">
            <h2 class="uppercase text-3xl font-bold">
                Personal Access Tokens
            </h2>

            <a href="#" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-blue-400" tabindex="-1" @click.prevent="modal.createToken = true">
                Create New Token
            </a>
        </div>

        <div class="mt-4">
            <!-- No Tokens Notice -->
            <template x-if="tokens.length === 0">
                <p>
                    You have not created any personal access token.
                </p>
            </template>

            <!-- Personal Access Tokens -->
            <template x-if="tokens.length > 0">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="p-4 border border-blue-400 text-left">Name</th>
                            <th class="p-4 border border-blue-400 w-32">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="token in tokens">
                            <tr>
                                <!-- Client Name -->
                                <td class="p-4" x-text="token.name"></td>

                                <!-- Delete Button -->
                                <td class="p-4 text-center">
                                    <a href="#" class="underline text-red-400" @click.prevent="remove(token)">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>
        </div>
    </div>

    <!-- Create Token Modal -->
    <x-modal id="modal-create-token" x-show="modal.createToken">
        <x-slot name="header">
            <div class="flex flex-wrap items-center justify-between -mx-4">
                <div class="px-4">
                    <h4 class="uppercase text-3xl font-bold">Create Token</h4>
                </div>

                <div class="px-4">
                    <button type="button" aria-hidden="true" @click="modal.createToken = false">&times;</button>
                </div>
            </div>
        </x-slot>

        <div class="my-4">
            <!-- Form Errors -->
            <template x-if="form.errors.length > 0">
                <div class="mb-4 text-red-400">
                    <p class="mb-2"><strong>Whoops!</strong> Something went wrong!</p>
                    <ul class="pl-4 list-disc">
                        <template x-for="error in form.errors">
                            <li x-text="error"></li>
                        </template>
                    </ul>
                </div>
            </template>

            <!-- Create Token Form -->
            <form role="form" @submit.prevent="store()">
                <!-- Name -->
                <div class="md:flex my-2 flex-wrap justify-between">
                    <label for="create-token-name">Name</label>

                    <input id="create-token-name" type="text" class="w-full my-2 py-2 px-4 placeholder-gray-400" name="name" x-model="form.name">
                </div>

                <!-- Scopes -->
                <template x-if="scopes.length > 0">
                    <div class="md:flex my-2 flex-wrap justify-between">
                        <label>Scopes</label>

                        <template x-for="scope in scopes">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           @click="toggleScope(scope.id)"
                                           :checked="scopeIsAssigned(scope.id)">

                                    <span x-text="scope.id"></span>
                                </label>
                            </div>
                        </template>
                    </div>
                </template>
            </form>
        </div>

        <x-slot name="actions">
            <div class="flex flex-wrap justify-end -mx-4">
                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-red-400" @click="modal.createToken = false">Close</button>
                </div>

                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-blue-400" @click="store()">
                        Create
                    </button>
                </div>
            </div>
        </x-slot>
    </x-modal>

    <!-- Access Token Modal -->
    <x-modal id="modal-access-token" x-show="modal.accessToken">
        <x-slot name="header">
            <div class="flex flex-wrap items-center justify-between -mx-4">
                <div class="px-4">
                    <h4 class="uppercase text-3xl font-bold">Personal Access Token</h4>
                </div>

                <div class="px-4">
                    <button type="button" aria-hidden="true" @click="modal.accessToken = false">&times;</button>
                </div>
            </div>
        </x-slot>

        <div class="my-4">
            <p>
                Here is your new personal access token. This is the only time it will be shown so don't lose it!
                You may now use this token to make API requests.
            </p>

            <code class="block mt-4 p-4 break-all border border-blue-400" x-text="accessToken"></code>
        </div>

        <x-slot name="actions">
            <div class="flex flex-wrap justify-end -mx-4">
                <div class="px-4">
                    <button type="button" class="px-12 py-2 text-sm text-white rounded-full block-inline bg-red-400" @click="modal.accessToken = false">Close</button>
                </div>
            </div>
        </x-slot>
    </x-modal>
</div>

<script>
    function tokenData() {
        return {
            accessToken: null,

            modal: {
                createToken: false,
                accessToken: false,
            },

            tokens: [],
            scopes: [],

            form: {
                name: '',
                scopes: [],
                errors: []
            },

            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                    .then(response => {
                        this.scopes = response.data;
                    });
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                    .then(response => {
                        this.form.name = '';
                        this.form.scopes = [];
                        this.form.errors = [];

                        this.tokens.push(response.data.token);

                        this.showAccessToken(response.data.accessToken);
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s === scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                this.modal.createToken = false;
                this.accessToken = accessToken;
                this.modal.accessToken = true;
            },

            /**
             * Remove the given token.
             */
            remove(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                    .then(() => {
                        this.getTokens();
                    });
            },
        }
    }
</script>

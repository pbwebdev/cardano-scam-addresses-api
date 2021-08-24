<div x-data="authorizedData()" x-init="prepareComponent()" class="max-w-7xl mx-auto py-12 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="flex justify-between items-center">
            <h2 class="uppercase text-3xl font-bold">
                Authorized Applications
            </h2>
        </div>

        <div class="mt-4">
            <!-- No Tokens Notice -->
            <template x-if="tokens.length === 0">
                <p>
                    You have not authorized any application.
                </p>
            </template>

            <!-- Authorized Tokens -->
            <template x-if="tokens.length > 0">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="p-4 border border-blue-400 text-left">Name</th>
                            <th class="p-4 border border-blue-400 w-72">Scopes</th>
                            <th class="p-4 border border-blue-400 w-32">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="token in tokens">
                            <tr>
                                <!-- Client Name -->
                                <td class="p-4" x-text="token.client.name" ></td>

                                <!-- Scopes -->
                                <td class="p-4">
                                    <template x-if="token.scopes.length > 0">
                                        <span x-text="token.scopes.join(', ')"></span>
                                    </template>
                                </td>

                                <!-- Revoke Button -->
                                <td class="p-4 text-center">
                                    <a href="#" class="underline text-red-400" @click.prevent="revoke(token)">
                                        Revoke
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>
        </div>
    </div>
</div>

<script>
    function authorizedData() {
        return {
            tokens: [],

            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/tokens/' + token.id)
                    .then(() => {
                        this.getTokens();
                    });
            },
        }
    }
</script>

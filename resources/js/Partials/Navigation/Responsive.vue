<template>
    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
         class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
            </jet-responsive-nav-link>

            <jet-responsive-nav-link :href="route('addresses')"
                                     :active="route().current('addresses')">
                Addresses
            </jet-responsive-nav-link>

            <jet-responsive-nav-link :href="route('websites')"
                                     :active="route().current('websites')">
                Websites
            </jet-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div v-if="$page.props.user" class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3">
                    <img class="h-10 w-10 rounded-full object-cover"
                         :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                </div>

                <div>
                    <div class="font-medium text-base text-gray-800">{{ $page.props.user.name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <jet-responsive-nav-link :href="route('profile.show')"
                                         :active="route().current('profile.show')">
                    Profile
                </jet-responsive-nav-link>

                <jet-responsive-nav-link :href="route('api-tokens.index')"
                                         :active="route().current('api-tokens.index')"
                                         v-if="$page.props.jetstream.hasApiFeatures">
                    API Tokens
                </jet-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" @submit.prevent="$emit('logout')">
                    <jet-responsive-nav-link as="button">
                        Log Out
                    </jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                <template v-if="$page.props.jetstream.hasTeamFeatures">
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Manage Team
                    </div>

                    <!-- Team Settings -->
                    <jet-responsive-nav-link :href="route('teams.show', $page.props.user.current_team)"
                                             :active="route().current('teams.show')">
                        Team Settings
                    </jet-responsive-nav-link>

                    <jet-responsive-nav-link :href="route('teams.create')"
                                             :active="route().current('teams.create')"
                                             v-if="$page.props.jetstream.canCreateTeams">
                        Create New Team
                    </jet-responsive-nav-link>

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Switch Teams
                    </div>

                    <template v-for="team in $page.props.user.all_teams" :key="team.id">
                        <form @submit.prevent="$emit('switchToTeam', team)">
                            <jet-responsive-nav-link as="button">
                                <div class="flex items-center">
                                    <svg v-if="team.id == $page.props.user.current_team_id"
                                         class="mr-2 h-5 w-5 text-green-400" fill="none"
                                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>{{ team.name }}</div>
                                </div>
                            </jet-responsive-nav-link>
                        </form>
                    </template>
                </template>
            </div>
        </div>

        <div v-else-if="$page.props.canLogin" class="py-4 pb-3 border-t border-gray-200 space-y-1">
            <jet-responsive-nav-link :href="route('login')"
                                     :active="route().current('login')">
                Login
            </jet-responsive-nav-link>

            <jet-responsive-nav-link :href="route('register')"
                                     :active="route().current('register')"
                                     v-if="$page.props.canRegister">
                Register
            </jet-responsive-nav-link>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'

    export default defineComponent({
        props: {
            showingNavigationDropdown: Boolean,
        },

        components: {
            JetResponsiveNavLink,
        },
    })
</script>

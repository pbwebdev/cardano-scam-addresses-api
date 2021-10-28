<template>
    <nav class="bg-white border-b border-gray-100">
        <primary-navigation :showing-navigation-dropdown="showingNavigationDropdown"
                            @toggleNavigationDropdown="toggleNavigationDropdown"
                            @switchTeam="switchToTeam"
                            @logout="logout">
            <template v-for="(label, name, index) in links" :key="index">
                <jet-nav-link :href="route(name)" :active="route().current(name)">
                    {{ label }}
                </jet-nav-link>
            </template>
        </primary-navigation>

        <responsive-navigation :showing-navigation-dropdown="showingNavigationDropdown"
                               @switchTeam="switchToTeam"
                               @logout="logout">
            <template v-for="(label, name, index) in links" :key="index">
                <jet-responsive-nav-link :href="route(name)" :active="route().current(name)">
                    {{ label }}
                </jet-responsive-nav-link>
            </template>
        </responsive-navigation>
    </nav>
</template>

<script>
    import { defineComponent } from 'vue'
    import PrimaryNavigation from '@/Partials/Navigation/Primary'
    import ResponsiveNavigation from '@/Partials/Navigation/Responsive'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'

    export default defineComponent({
        components: {
            PrimaryNavigation,
            ResponsiveNavigation,
            JetNavLink,
            JetResponsiveNavLink,
        },

        data() {
            return {
                showingNavigationDropdown: false,
                links: {
                    dashboard: 'Dashboard',
                    addresses: 'Addresses',
                    websites: 'Websites',
                    submissions: 'Submissions',
                }
            }
        },

        methods: {
            toggleNavigationDropdown() {
                this.showingNavigationDropdown = !this.showingNavigationDropdown
            },

            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    })
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <primary-navigation :showing-navigation-dropdown="showingNavigationDropdown"
                            @toggleNavigationDropdown="toggleNavigationDropdown"
                            @switchTeam="switchToTeam"
                            @logout="logout">
        </primary-navigation>

        <responsive-navigation :showing-navigation-dropdown="showingNavigationDropdown"
                               @switchTeam="switchToTeam"
                               @logout="logout">
        </responsive-navigation>
    </nav>
</template>

<script>
    import { defineComponent } from 'vue'
    import PrimaryNavigation from '@/Partials/Navigation/Primary'
    import ResponsiveNavigation from '@/Partials/Navigation/Responsive'

    export default defineComponent({
        components: {
            PrimaryNavigation,
            ResponsiveNavigation,
        },

        data() {
            return {
                showingNavigationDropdown: false,
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

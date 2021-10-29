<template>
    <data-listing
        :items="state.items"
        @manageItem="manageAction"
    />

    <data-pagination
        :pages="state.pages"
        @loadItems="loadData"
    />

    <data-modal
        :title="modalTitle"
        :form-data="{
            validationMessage: state.formMessage,
            isProcessing: state.formProcessing,
            fieldValue: state.fieldValue,
            fieldErrors: state.fieldErrors,
        }"
        :is-active="state.modalActive"
        :managed-id="state.managedId"
        @toggleStatus="toggleModal"
        @updateFieldValue="updateFieldValue"
        @createItem="createAction"
        @editItem="editAction"
        @RemoveItem="removeAction"
    />
</template>

<script>
    import { computed, defineComponent, onMounted } from 'vue';
    import composableResource from "@/utilities/composableResource";
    import DataListing from '@/Partials/Data/Listing'
    import DataPagination from '@/Partials/Data/Pagination'
    import DataModal from '@/Partials/Data/Modal'
    import JetButton from "@/Jetstream/Button";

    export default defineComponent({
        components: {
            DataListing,
            DataPagination,
            DataModal,
            JetButton,
        },

        props: {
            routeBaseName: {
                type: String,
                required: true,
            },
            headingTitle: {
                type: String,
                required: true,
            },
        },

        setup(props) {
            const {
                state,
                toggleModal,
                updateFieldValue,
                loadData,
                manageAction,
                createAction,
                editAction,
                removeAction
            } = composableResource(props.routeBaseName)

            const modalTitle = computed(() => `${state.managedId ? 'Edit' : 'Add New'} ${props.headingTitle}`);

            onMounted(() => {
                loadData();
            })

            return {
                state,
                modalTitle,
                toggleModal,
                updateFieldValue,
                loadData,
                manageAction,
                createAction,
                editAction,
                removeAction,
            }
        },
    })
</script>

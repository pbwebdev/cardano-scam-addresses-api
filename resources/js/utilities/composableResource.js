import { reactive } from 'vue'

export default function composableResource(routeBaseName) {
    const state = reactive({
        items: [],
        pages: [],
        managedId: 0,
        modalActive: false,
        fieldValue: null,
        formProcessing: false,
        fieldError: '',
        formMessage: '',
    });

    function toggleModal(status) {
        state.modalActive = status;
    }

    function updateFieldValue(newValue) {
        state.fieldValue = newValue;
    }

    function resetData(manageId = 0, openModal = false) {
        toggleModal(openModal);

        state.managedId = manageId;
        state.fieldValue = null;
        state.formMessage = '';
        state.fieldError = '';
    }

    function loadData(url) {
        if (!url) {
            url = route(`${routeBaseName}.index`, {
                page: new URLSearchParams(window.location.search).get('page')
            })
        }

        const response = axios.get(url);

        response.then(data => {
            state.items = data?.data?.data || [];
            state.pages = data?.data?.pagination || [];
        });
    }

    function createAction() {
        state.formProcessing = true;

        const response = axios.post(route(`${routeBaseName}.index`), {
            address: state.fieldValue,
        });

        response.then(data => {
            resetData();

            const address = data?.data?.data || false;

            if (address) {
                state.items.push(address);
            }

            return data;
        }).catch(error => {
            state.formMessage = error?.response?.data?.message || '';
            state.fieldError = error?.response?.data?.errors?.address[0] || '';
        }).finally(() => {
            state.formProcessing = false;
        });
    }

    function manageAction(id) {
        resetData(id, true)

        if (id) {
            const index = state.items.findIndex(object => object.id === id);
            state.fieldValue = state.items[index].address;
        }
    }

    function editAction() {
        state.formProcessing = true;

        const response = axios.patch(route(`${routeBaseName}.update`, state.managedId), {
            address: state.fieldValue,
        });

        response.then(data => {
            resetData();

            const address = data?.data?.data || false;

            if (address) {
                const index = state.items.findIndex(object => object.id === address.id);

                state.items[index] = address;
            }

            return data;
        }).catch(error => {
            state.formMessage = error?.response?.data?.message || '';
            state.fieldError = error?.response?.data?.errors?.address[0] || '';
        }).finally(() => {
            state.formProcessing = false;
        });
    }

    function removeAction() {
        state.formProcessing = true;

        const response = axios.delete(route(`${routeBaseName}.destroy`, state.managedId));

        response.then(data => {
            const index = state.items.findIndex(object => object.id === state.managedId);

            resetData();
            state.items.splice(index, 1);

            return data;
        }).catch(error => {
            state.formMessage = error?.response?.data?.message || '';
            state.fieldError = error?.response?.data?.errors?.address[0] || '';
        }).finally(() => {
            state.formProcessing = false;
        });
    }

    return {
        state,
        toggleModal,
        updateFieldValue,
        loadData,
        createAction,
        manageAction,
        editAction,
        removeAction,
    }
}

<template>
    <app-layout title="Submit">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Submit
            </h1>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-form-section @submitted="pushSubmission">
                <template #title>
                    Submit a scam
                </template>

                <template #description>
                    Provide information
                </template>

                <template #form>
                    <div class="col-span-6">
                        <jet-label for="transaction" value="Transaction Hash" />
                        <jet-input id="transaction" type="text" class="mt-1 block w-full"
                                   v-model="formData.inputs.transaction" autofocus />
                        <jet-input-error v-for="(error, index) in formData.errors.transaction" :key="index"
                                         :message="error" class="mt-2" />
                    </div>

                    <div class="col-span-6">
                        <jet-label for="description" value="Description" />
                        <jet-textarea id="description" class="mt-1 block w-full" rows="6"
                                      v-model="formData.inputs.description" autofocus />
                        <jet-input-error v-for="(error, index) in formData.errors.description" :key="index"
                                         :message="error" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="formData.recentlySuccessful" class="mr-3">
                        Submitted.
                    </jet-action-message>

                    <jet-action-message :on="formData.validationMessage" class="mr-3">
                        {{ formData.validationMessage }}
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': formData.processing }" :disabled="formData.processing">
                        Submit
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent, reactive } from 'vue'
    import AppLayout from '@/Layouts/AppLayout'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetLabel from '@/Jetstream/Label'
    import JetInput from '@/Jetstream/Input'
    import JetTextarea from '@/Jetstream/Textarea'
    import JetInputError from '@/Jetstream/InputError'
    import JetButton from '@/Jetstream/Button'

    export default defineComponent({
        components: {
            AppLayout,
            JetFormSection,
            JetActionSection,
            JetActionMessage,
            JetLabel,
            JetInput,
            JetTextarea,
            JetInputError,
            JetButton,
        },

        setup() {
            const formData = reactive({
                inputs: {
                    transaction: null,
                    description: null,
                },
                errors: {
                    transaction: null,
                    description: null,
                },
                validationMessage: null,
                recentlySuccessful: false,
                processing: false,
            });

            const resetData = () => {
                formData.inputs = {
                    transaction: null,
                    description: null,
                };
                formData.errors = {
                    transaction: null,
                    description: null,
                };
                formData.validationMessage = null;
                formData.recentlySuccessful = false;
                formData.processing = false;
            }

            const pushSubmission = () => {
                formData.formProcessing = true;

                const response = axios.post(route(`submissions.index`), {
                    transaction: formData.inputs.transaction,
                    description: formData.inputs.description,
                });

                response.then(data => {
                    resetData();

                    formData.recentlySuccessful = true;

                    return data;
                }).catch(error => {
                    const message = error?.response?.data?.message;

                    if (message) {
                        formData.validationMessage = message;
                    }

                    const errors = error?.response?.data?.errors;

                    if (errors) {
                        formData.errors = errors;
                    }
                }).finally(() => {
                    formData.formProcessing = false;
                });
            };

            return {
                formData,
                pushSubmission,
            }
        }
    })
</script>

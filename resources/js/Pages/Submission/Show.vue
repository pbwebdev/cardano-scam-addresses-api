<template>
    <app-layout title="Submitted">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Submitted
            </h1>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-form-section @submitted="updateSubmission">
                <template #title>
                    Submitted scam
                </template>

                <template #description>
                    Provided information
                </template>

                <template #form>
                    <div class="col-span-6">
                        <jet-label for="transaction" value="Transaction Hash" />
                        <jet-input id="transaction" type="text" readonly class="mt-1 block w-full"
                                   v-model="submission.transaction" autofocus />
                        <jet-input-error v-for="(error, index) in formData.errors.transaction" :key="index"
                                         :message="error" class="mt-2" />
                    </div>

                    <div class="col-span-6">
                        <jet-label for="description" value="Description" />
                        <jet-textarea id="description" readonly class="mt-1 block w-full" rows="6"
                                      v-model="submission.description" autofocus />
                        <jet-input-error v-for="(error, index) in formData.errors.description" :key="index"
                                         :message="error" class="mt-2" />
                    </div>

                    <div class="col-span-6">
                        <jet-label for="status" value="Status" />

                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="statusName in statusNames" :key="statusName">
                                <label class="flex items-center">
                                    <input type="radio" :value="statusName" v-model="formData.statusValue" />
                                    <span class="ml-2 text-sm text-gray-600">{{ statusName }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="formData.recentlySuccessful" class="mr-3">
                        Updated.
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
    import JetDangerButton from '@/Jetstream/DangerButton'

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
            JetDangerButton,
        },

        'props': {
            submission: {
                type: Object,
                required: true,
            },
            statusNames: {
                type: Array,
                required: true,
            }
        },

        setup(props) {
            const formData = reactive({
                statusValue: props.submission.status,
                errors: {
                    transaction: null,
                    description: null,
                },
                validationMessage: null,
                recentlySuccessful: false,
                processing: false,
            });

            const resetData = () => {
                formData.errors = {
                    transaction: null,
                    description: null,
                };
                formData.validationMessage = null;
                formData.recentlySuccessful = false;
            }

            const updateSubmission = () => {
                resetData();
                formData.processing = true;

                const response = axios.patch(route(`submissions.update`, props.submission.id), {
                    transaction: props.submission.transaction,
                    description: props.submission.description,
                    status: formData.statusValue,
                });

                response.then(data => {
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
                    formData.processing = false;
                });
            };

            return {
                formData,
                updateSubmission,
            }
        }
    })
</script>

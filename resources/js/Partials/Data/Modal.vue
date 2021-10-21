<template>
    <jet-dialog-modal :show="isActive" @close="$emit('toggleStatus', false)">
        <template #title>
            {{ title }}
        </template>

        <template #content>
            <p v-if="formData.validationMessage" class="mb-2 font-medium text-red-600">
                {{ formData.validationMessage }}
            </p>

            <jet-input type="text"
                       class="w-full"
                       :modelValue="formData.fieldValue"
                       @update:modelValue="updateFieldValue"
                       required autofocus
            />

            <jet-input-error :message="formData.fieldError" class="mt-2" />
        </template>

        <template #footer>
            <div class="space-x-2 space-y-2">
                <jet-danger-button v-if="managedId" @click="$emit('removeItem')"
                                   :class="{ 'opacity-25': formData.isProcessing }"
                                   :disabled="formData.isProcessing">
                    Delete
                </jet-danger-button>

                <jet-secondary-button @click="$emit('toggleStatus', false)">
                    Cancel
                </jet-secondary-button>

                <jet-button v-if="managedId" @click="$emit('editItem')"
                            :class="{ 'opacity-25': formData.isProcessing }"
                            :disabled="formData.isProcessing">
                    Update
                </jet-button>

                <jet-button v-else @click="$emit('createItem')"
                            :class="{ 'opacity-25': formData.isProcessing }"
                            :disabled="formData.isProcessing">
                    Save
                </jet-button>
            </div>
        </template>
    </jet-dialog-modal>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'

    export default defineComponent({
        components: {
            JetDialogModal,
            JetInput,
            JetLabel,
            JetInputError,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
        },

        props: {
            title: {
                type: String,
                required: true,
            },
            formData: {
                type: Object,
                required: true,
            },
            isActive: {
                type: Boolean,
                required: true,
            },
            managedId: {
                type: Number,
                required: true,
            },
        },

        emits: [
            'toggleStatus',
            'updateFieldValue',
            'createItem',
            'editItem',
            'removeItem',
        ],

        setup(_, {emit}) {
            const updateFieldValue = value => emit('updateFieldValue', value)

            return {
                updateFieldValue,
            }
        },
    })
</script>

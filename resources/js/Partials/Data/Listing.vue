<template>
    <div v-if="items.length > 0"
         class="w-full sm:max-w-4xl mx-auto my-6 p-6 bg-white shadow-md break-words sm:rounded-lg">
        <ul class="list-decimal ml-10">
            <li v-for="item in items" :key="item.id">
                <a href="#"
                   class="text-[#6875F5]"
                   @click="$emit('manageItem', item.id)"
                   v-if="$page.props.isAdmin"
                >
                    {{ item.address }}
                </a>
                <span v-else>{{ item.address }}</span>
            </li>
        </ul>
    </div>
</template>

<script>
    import { defineComponent } from "vue"

    export default defineComponent({
        props: {
            items: {
                type: Array,
                required: true,
            },
        },

        emits: [
            'manageItem',
        ],
    })
</script>

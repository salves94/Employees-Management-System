<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    mode: {
        type: String,
        default: 'add'
    },
});

const classes = computed(() => {
    if (props.mode == 'add') {
        return 'bg-blue-600 hover:bg-blue-800';
    } else if (props.mode == 'edit') {
        return 'bg-yellow-600 hover:bg-yellow-800';
    } else if (props.mode == 'delete') {
        return 'bg-red-600 hover:bg-red-800';
    } else if (props.mode == 'view') {
        return 'bg-green-600 hover:bg-green-800';
    }
    return '';
});
</script>

<template>
    <Link v-if="href"
        :href="href"
        class="px-3 py-2 mr-2 rounded text-white text-sm font-bold whitespace-no-wrap"
        :class="classes"
    >
        <slot />
    </Link>
    <button v-else
        class="px-3 py-2 mr-2 rounded text-white text-sm font-bold whitespace-no-wrap"
        :class="classes"
    >
        <slot />
    </button>
</template>
<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-tf-blue-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-tf-blue-500 transition duration-150 ease-in-out dark:border-tf-blue-600 dark:text-gray-100'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-700 dark:focus:text-gray-300 dark:focus:border-gray-700';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

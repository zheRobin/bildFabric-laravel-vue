<script setup>
import {usePage} from "@inertiajs/vue3";

const logo = usePage().props.mainLogoPath;
</script>

<template>
    <img :src="logo" alt="Bildfabrik">
</template>

<script setup>
import ApiTokenManager from 'Jetstream/Pages/API/Partials/ApiTokenManager.vue';
import AppLayout from 'Jetstream/Layouts/AppLayout.vue';

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
    apiDocumentations: Object,
});
</script>

<template>
    <AppLayout title="API Tokens">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('API Tokens') }}
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <ApiTokenManager
                    :tokens="tokens"
                    :available-permissions="availablePermissions"
                    :default-permissions="defaultPermissions"
                    :apiDocumentations="apiDocumentations"
                />
            </div>
        </div>
    </AppLayout>
</template>

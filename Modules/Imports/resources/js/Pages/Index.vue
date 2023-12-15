<script setup>
import AppLayout from 'Jetstream/Layouts/AppLayout.vue';
import CollectionDataTable from "Modules/Imports/resources/js/Components/CollectionDataTable.vue";
import UploadFileForm from "Modules/Imports/resources/js/Pages/Partials/UploadFileForm.vue";
import DashboardPanel from "Jetstream/Components/DashboardPanel.vue";
import EmptyCollection from "Modules/Collections/resources/js/Components/EmptyCollection.vue";
import { ref } from 'vue';

const props = defineProps({
    items: Object,
    permissions: Object,
});

// Make sure to use ref for reactivity
let itemsRef = ref(props.items);

// Deletes item from itemsRef list
const handleItemDeleted = (id) => {
    itemsRef.value.data = itemsRef.value.data.filter(item => item.id !== id);
};
const handleItemUploaded = () => {
    itemsRef.value = props.items;
};
</script>

<template>
    <AppLayout title="Import">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('Import') }}
            </h2>
        </template>

        <DashboardPanel>
            <EmptyCollection v-if="!$page.props.auth.user.current_collection" />

            <template v-else>
                <UploadFileForm v-if="permissions.canUpdateCollection" :hasItems="!!itemsRef.data.length" class="px-2 sm:px-0 mb-2" @itemUploaded="handleItemUploaded"/>

                <CollectionDataTable
                  v-if="itemsRef.data.length"
                  :canUpdateCollection="permissions.canUpdateCollection"
                  :items="itemsRef"
                  @deleteItem="handleItemDeleted"
                  :headers="$page.props.auth.user.current_collection.headers" class="" />
            </template>
        </DashboardPanel>
    </AppLayout>
</template>

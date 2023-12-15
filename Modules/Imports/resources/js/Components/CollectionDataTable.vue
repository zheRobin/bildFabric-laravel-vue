<script setup>
import Pagination from "Jetstream/Components/Pagination.vue";
import DangerButton from "Jetstream/Components/DangerButton.vue";
import {MinusCircleIcon} from "@heroicons/vue/20/solid";
import axios from "axios";
const props = defineProps({
    items: Object,
});
const deleteImage = (file) => {
    axios.delete(route('collection-items.delete', file.id), {
        errorBag: 'errors',
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            changePreset(null);
            resetForm();
            notify({
                group: "success",
                title: trans("Success"),
                text: trans("Image deleted!")
            }, 4000)
        },
    })
}
</script>

<template>
    <div>
        <Pagination :links="items.links" />
        <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
            <li v-for="file in items.data" :key="file.source" class="group">
                <div class="aspect-h-7 aspect-w-10 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 relative">
                    <img :src="file.source" alt="" class="pointer-events-none object-cover group-hover:opacity-75" />
                    <button type="button" class="absolute inset-0 focus:outline-none">
                        <span class="sr-only">View details for {{ file.title }}</span>
                    </button>
                    <div class="absolute p-1">
                        <div class="relative">
                            <DangerButton v-on:click="deleteImage(file)" class="hidden absolute right-0 group-hover:flex gap-1">
                                {{$t('Delete')}}
                                <MinusCircleIcon class="-mr-0.5 w-4" aria-hidden="true" />
                            </DangerButton>
                        </div>
                    </div>
                </div>
                <p class="mt-2 block truncate text-sm font-medium text-gray-900">{{ file.title }}</p>
                <p class="block text-sm font-medium text-gray-500">{{ file.size }}</p>
            </li>
        </ul>
    </div>

</template>

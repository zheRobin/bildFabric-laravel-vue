<script setup>
import Pagination from "Jetstream/Components/Pagination.vue";
import {computed} from "vue";
import CollectionItem from "Modules/Imports/resources/js/Components/CollectionItem.vue";
import CollectionHeader from "Modules/Imports/resources/js/Components/CollectionHeader.vue";

const props = defineProps({
    items: Object,
    headers: Array,
    canUpdateCollection: Boolean,
});

const colsCount = computed(() => props.items.data.length);
const gridColsCount = computed(() => `grid-cols-${colsCount.value}`);

const colNumber = (index) => {
    return props.items.per_page * (props.items.current_page - 1) + (index + 1);
}
</script>

<template>
    <div>
        <Pagination :links="items.links" />
        <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
            <li v-for="file in items.data" :key="file.source" class="relative">
                <div class="group aspect-h-7 aspect-w-10 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
                    <img :src="file.source" alt="" class="pointer-events-none object-cover group-hover:opacity-75" />
                    <button type="button" class="absolute inset-0 focus:outline-none">
                    <span class="sr-only">View details for {{ file.title }}</span>
                    </button>
                </div>
            <p class="pointer-events-none mt-2 block truncate text-sm font-medium text-gray-900">{{ file.title }}</p>
            <p class="pointer-events-none block text-sm font-medium text-gray-500">{{ file.size }}</p>
            </li>
        </ul>
        <!-- <div class="bg-white shadow">
            <div class="grid text-sm border-gray-400" :class="gridColsCount">
                <div class="" v-for="(item, index) in items.data" :key="`item-${item.id}-${index}`" :class="{'bg-gray-100': index % 2 !== 0}">
                    <div class="p-4 bg-gray-200 h-14 text-center">
                        <span class="font-semibold text-gray-600"> {{ colNumber(index) }} </span>
                    </div>
                    <CollectionItem :canUpdate="canUpdateCollection" :item="item" :colsCount="colsCount" :colNumber="index" />
                </div>
            </div>
        </div> -->
    </div>

</template>

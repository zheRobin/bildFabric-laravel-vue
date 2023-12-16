<script setup>
import {computed, ref, watch} from "vue";
import {ChevronRightIcon, ChevronLeftIcon} from '@heroicons/vue/20/solid';
import PrimaryButton from "Jetstream/Components/PrimaryButton.vue";
import {iterateCollectionItems} from "Modules/OpenAI/resources/js/axios";
import LanguageInput from "Modules/OpenAI/resources/js/Components/LanguageInput.vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    languages: Array,
    languageId: Number,
    canChangeLanguage: Boolean,
    title: Object
});

const emit = defineEmits(['itemChanged', 'update:inputLanguage']);

const findLanguage = (languageId) => {
    if (languageId) {
        currentLanguage.value = props.languages.find((language) => language.id === languageId)
    } else {
        currentLanguage.value = null;
    }
}

const currentLanguage = ref(null);
findLanguage(props.languageId);

watch(() => props.languageId, (langId) => {
    findLanguage(langId);
});

const currentItem = ref([]);

const source = computed(() => {
    return currentItem.value
        ? currentItem.value.source
        : null;
});

const currentPage = ref(1);
const lastPage = ref(null);
const findElementHeader = () => {
    let title = '';
    if (currentItem.value.title !== undefined) {
        title = currentItem.value.title;

        if (title) {
            let maxLength = 70;
            if (window.innerWidth < 768) {
                maxLength = 25;
            } else if (window.innerWidth < 1024) {
                maxLength = 45;
            }
            if (title.length > maxLength) {
                return title.slice(0, maxLength - 5) + '...';
            }
            return title;
        }
    }
}
const collectionItem = (page) => {
    iterateCollectionItems(page)
        .then((response) => {
            const item = response.data.data.find(() => true);

            currentItem.value = item;
            currentPage.value = response.data.current_page;
            lastPage.value = response.data.last_page;

            emit('itemChanged', currentItem.value);
    });
}

collectionItem(currentPage);

const nextItem = () => {
    if ((currentPage.value + 1) <= lastPage.value ) {
        collectionItem(currentPage.value + 1);
    }
}

const previousItem = () => {
    if ((currentPage.value - 1) >= 1) {
        collectionItem(currentPage.value -1);
    }
}

const changeLanguage = (language) => {
    currentLanguage.value = language;
    emit('update:inputLanguage', language);
}

const translateItem = () => {
    axios.post(route('deepl.translate-collection-item', currentItem.value), {
        languageCode: currentLanguage.value.code,
    }).then((response) => {
        currentItem.value.data = response.data.data;
    }).catch((error) => {
        // TODO: notify about the error
    });
}
</script>

<template>
    <div class="bg-gray-50 rounded">
        <div class="px-6 py-3 items-center">
            <div class="flex items-center justify-end">
                <div class="flex items-center hidden">
                    <LanguageInput :disabled="!canChangeLanguage" @update:modelValue="changeLanguage" v-model="currentLanguage" :languages="languages" />

                    <PrimaryButton v-if="currentItem && currentLanguage && canChangeLanguage" @click="translateItem" class="inline-flex ml-3"> {{ $t('Translate') }} </PrimaryButton>
                </div>

                <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900 truncate">
                        <div v-if="findElementHeader()" class="flex">
                            <div>{{findElementHeader()}}</div>
                            <span class="ml-2 mr-2">-</span>
                            <span class="ml-2 mr-4">
                            {{ `#${currentPage}` }}
                            </span>
                        </div>
                        <div v-else>
                            <span class="ml-2 mr-4">
                            {{ `#${currentPage}` }}
                            </span>
                        </div>
                    </div>

                    <span class="isolate inline-flex rounded-md shadow-sm">
                        <button type="button" @click="previousItem" :disabled="currentPage === 1" :class="currentPage > 1 ? 'hover:bg-gray-50 focus:z-10' : ''" class="relative inline-flex items-center rounded-l-md bg-white px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300">
                            <span class="sr-only"> {{ $t('Previous') }} </span>
                            <ChevronLeftIcon class="h-4 w-4" :class="currentPage > 1 ? 'text-black' : ''" aria-hidden="true" />
                        </button>
                        <button type="button" @click="nextItem" :disabled="currentPage === lastPage" :class="currentPage !== lastPage ? 'hover:bg-gray-50 focus:z-10' : ''" class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300">
                            <span class="sr-only">{{$t('Next')}}</span>
                            <ChevronRightIcon class="h-4 w-4" :class="currentPage !== lastPage ? 'text-black' : ''" aria-hidden="true"/>
                        </button>
                    </span>
                </div>
            </div>
        </div>

<!--        <div v-if="loading" class="min-h-56 flex justify-center items-center mb-6">-->
<!--            <Spinner class="" />-->
<!--        </div>-->

        <div class="aspect-h-7 aspect-w-10 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 relative">
            <img :src="source" class="pointer-events-none object-cover group-hover:opacity-75"/>
        </div>
    </div>
</template>

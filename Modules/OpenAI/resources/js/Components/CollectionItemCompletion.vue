<script setup>
import {computed, ref, watch} from "vue";
import PrimaryButton from "Jetstream/Components/PrimaryButton.vue";
import {streamItemCompletion} from "Modules/OpenAI/resources/js/event-streams";
import Spinner from "Jetstream/Components/Spinner.vue";
import LanguageInput from "Modules/OpenAI/resources/js/Components/LanguageInput.vue";
import {notify} from "notiwind";
import {trans} from "laravel-vue-i18n";
import CopyToClipboard from "Jetstream/Components/CopyToClipboard.vue";
import SecondaryButton from "Jetstream/Components/SecondaryButton.vue";

const props = defineProps({
    item: Object,
    preset: Object,
    languageId: Number,
    languages: Array,
    updatePreset: Function,
    canChangeLanguage: Boolean,
    needPresetUpdate: Boolean,
});

const generationText = computed(() => {
    return props.needPresetUpdate
        ? `${trans('Save')} & ${trans('Generate')}`
        :  trans('Generate');
});

const emit = defineEmits(['update:outputLanguage']);

watch(() => props.item, (value, oldValue) => {
    if (oldValue) {
        generate();
    }
});

watch(() => props.preset.id, () => {
    purgeStream();
});

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

const generatedContent = ref('');

const currentEventSource = ref(null);

const generatingContent = ref(false);

const translatingContent = ref(false);

const loading = ref(false);

const setupStream = () => {
    generatingContent.value = true;

    currentEventSource.value = streamItemCompletion(props.preset.id, props.item.id, (data) => {
        // TODO: move away from callable
        if (loading.value) {
            triggerLoading(false);
        }

        generatedContent.value += data.content;
    }, () => {
        generatingContent.value = false;
        triggerLoading(false);
    }, (error) => {
        generatingContent.value = false;
        triggerLoading(false);
        notify({
            group: "error",
            title: trans("Error"),
            text: error
        }, 4000);
    });
}

const generate = async () => {
    await purgeStream();
    triggerLoading(true);

    if (props.needPresetUpdate) {
        props.updatePreset(() => setupStream());
    } else {
        setupStream();
    }
}

const clearOutput = () => {
    generatedContent.value = '';
}

const purgeStream = async () => {
    if (currentEventSource.value) {
        await currentEventSource.value.close();
    }

    generatingContent.value = false;
    loading.value = false;
    clearOutput();
}

const triggerLoading = (value) => {
    loading.value = value;
}

const changeLanguage = (language) => {
    currentLanguage.value = language;
    emit('update:outputLanguage', language);
}

const translateContent = () => {
    translatingContent.value = true;
    triggerLoading(true);
    axios.post(route('deepl.translate-text'), {
        content: generatedContent.value,
        languageCode: currentLanguage.value.code,
    }).then((response) => {
        generatedContent.value = response.data.content;
        triggerLoading(false);
        translatingContent.value = false;
    }).catch(error => {
        triggerLoading(false);
        translatingContent.value = false;
    });
}
</script>

<template>
    <div class="bg-gray-50 rounded mt-10">
        <div class="px-6 py-3 flex justify-between items-center border-b">
            <div class="flex items-center">
                <LanguageInput :disabled="!canChangeLanguage" @update:modelValue="changeLanguage" v-model="currentLanguage" :languages="languages" />

                <PrimaryButton @click="translateContent" v-if="generatedContent && currentLanguage && !generatingContent && canChangeLanguage" class="inline-flex ml-3"> {{$t('Translate')}} </PrimaryButton>
            </div>

            <div class="flex items-center">
                <CopyToClipboard v-if="!generatingContent && generatedContent" :content="generatedContent" class="mr-2" />

                <PrimaryButton @click="generate" v-if="!generatingContent" :class="{ 'opacity-50': generatingContent }">
                    {{ generationText }}
                </PrimaryButton>
                <SecondaryButton @click="purgeStream" v-else>
                    {{ $t('Cancel') }}
                </SecondaryButton>
            </div>
        </div>

        <div v-if="loading" class="min-h-56 flex justify-center items-center">
            <Spinner class="" />
        </div>

        <div v-else class="aspect-h-7 aspect-w-10 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 relative">
            {{ generatedContent }}
        </div>
    </div>
</template>

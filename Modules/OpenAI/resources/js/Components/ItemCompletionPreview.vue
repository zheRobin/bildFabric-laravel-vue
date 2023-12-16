<script setup>
import {ref, watch} from "vue";
import CollectionItemPreview from "Modules/OpenAI/resources/js/Components/CollectionItemPreview.vue";
import CollectionItemCompletion from "Modules/OpenAI/resources/js/Components/CollectionItemCompletion.vue";

const props = defineProps({
    preset: Object,
    languages: Array,
    updatePreset: Function,
    canChangeLanguage: Boolean,
    needPresetUpdate: Boolean,
});

const currentInputLanguage = ref(props.preset.input_language_id ?? null);
const currentOutputLanguage = ref(props.preset.output_language_id ?? null);

watch(() => props.preset, (preset) => {
    currentInputLanguage.value = preset
        ? preset.input_language_id
        : null;
    currentOutputLanguage.value = preset
        ? preset.output_language_id
        : null;
});

const emit = defineEmits(['update:inputLanguage', 'update:outputLanguage']);

const previewItem = ref(null);

const changePreviewItem = (value) => {
    previewItem.value = value;
}

const changeInputLanguage = (language) => {
    emit('update:inputLanguage', language);
}

const changeOutputLanguage = (language) => {
    emit('update:outputLanguage', language);
}

</script>

<template>
    <div class=" lg:grid lg:grid-cols-2 lg:gap-x-8">
        <div>
            <CollectionItemPreview @update:inputLanguage="changeInputLanguage"
                                   @itemChanged="changePreviewItem"
                                   :item="previewItem"
                                   :languages="languages"
                                   :languageId="currentInputLanguage"
                                   :canChangeLanguage="canChangeLanguage"
                                   class="mt-10" />
        </div>

        <div>
            <CollectionItemCompletion @update:outputLanguage="changeOutputLanguage"
                                      :preset="preset"
                                      :item="previewItem"
                                      :languages="languages"
                                      :languageId="currentOutputLanguage"
                                      :canChangeLanguage="canChangeLanguage"
                                      :updatePreset="updatePreset"
                                      :needPresetUpdate="needPresetUpdate"
                                      class="flex-col" />
        </div>
    </div>
</template>

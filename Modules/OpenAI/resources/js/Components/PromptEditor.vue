<script setup>
import {Mentionable} from "vue-mention";
import {ref, watch} from "vue";
import {trans} from "laravel-vue-i18n";

const props = defineProps({
    modelValue: String,
    text: String,
    attributes: {
        type: Array,
        default: [],
    },
    title: String,
    canEdit: Boolean,
});

const textInput = ref(null);

const emit = defineEmits(['update:modelValue']);

const promptText = ref(props.modelValue ?? '');

watch(() => props.modelValue, (value) => {
    promptText.value = value;
})

const attributesMentions = () => {
    const mentions = [];

    props.attributes.forEach((el) => {
        mentions.push({value: el.name, label: el.name});
    })

    return mentions;
}

const update = () => {
    emit('update:modelValue', promptText.value);
}
</script>

<template>
    <div>
        <div class="flex items-center mb-2">
            <label class="text-sm font-medium flex-1"> {{ title }} </label>
        </div>

        <textarea @input="update" ref="textInput" :disabled="!canEdit" v-model="promptText" :style="{resize: canEdit ? 'vertical' : 'none'}" :placeholder="trans('Enter text')" rows="8" name="comment" id="comment" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-tf-blue-500 sm:text-sm sm:leading-6" />
    </div>
</template>

<style>
.mention-item {
    padding: 4px 3px;
}

.mention-selected {
    background: rgb(241, 245, 249);
}
</style>

<script setup>
import ConfirmationModal from "Jetstream/Components/ConfirmationModal.vue";
import SecondaryButton from "Jetstream/Components/SecondaryButton.vue";
import DangerButton from "Jetstream/Components/DangerButton.vue";
import {ref, watch} from "vue";

const props = defineProps({
    name: String
})

const name = ref(props.name);

const emit = defineEmits(['delete']);

watch(() => props.name, (value) => {
    name.value = value;
});

const confirmingPresetDeletion = ref(false);

const deletePreset = () => {
    emit('delete');
    confirmingPresetDeletion.value = false;
};
</script>

<template>
    <span @click="confirmingPresetDeletion = true">
        <slot />
    </span>

    <ConfirmationModal :show="confirmingPresetDeletion" @close="confirmingPresetDeletion = false">
        <template #title>
            {{$t('Delete Compilation')}}
        </template>

        <template #content>
            {{$t('Are you sure you want to delete ')}}<span class="font-bold">{{ name }}</span> {{$t('compilation?')}}
        </template>

        <template #footer>
            <SecondaryButton @click="confirmingPresetDeletion = false">
                {{ $t('Cancel') }}
            </SecondaryButton>

            <DangerButton
                class="ml-3"
                @click="deletePreset"
            >
                {{$t('Delete Compilation')}}
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>

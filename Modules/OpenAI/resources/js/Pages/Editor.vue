<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {XCircleIcon, MinusCircleIcon, PencilSquareIcon, PlusCircleIcon, ArrowDownTrayIcon} from "@heroicons/vue/20/solid";
import {notify} from "notiwind";
import {isObject} from "Jetstream/utilities";
import AppLayout from "Jetstream/Layouts/AppLayout.vue";
import SelectMenu from "Jetstream/Components/SelectMenu.vue";
import TextInput from "Jetstream/Components/TextInput.vue";
import RangeSlider from "Jetstream/Components/RangeSlider.vue";
import PromptEditor from "Modules/OpenAI/resources/js/Components/PromptEditor.vue";
import PrimaryButton from "Jetstream/Components/PrimaryButton.vue";
import SecondaryButton from "Jetstream/Components/SecondaryButton.vue";
import DangerButton from "Jetstream/Components/DangerButton.vue";
import RenamePreset from "Modules/OpenAI/resources/js/Components/RenamePreset.vue";
import DeletePreset from "Modules/OpenAI/resources/js/Components/DeletePreset.vue";
import ItemCompletionPreview from "Modules/OpenAI/resources/js/Components/ItemCompletionPreview.vue";
import DashboardPanel from "Jetstream/Components/DashboardPanel.vue";
import EmptyCollection from "Modules/Collections/resources/js/Components/EmptyCollection.vue";
import EmptyImport from "Modules/Imports/resources/js/Components/EmptyImport.vue";
import {trans} from "laravel-vue-i18n";

const props = defineProps({
    selectedPreset: Object,
    presets: Array,
    models: Array,
    languages: Array,
    hasItems: Boolean,
    permissions: Object,
});
const selectedPreset = ref(null);
const selectedPresetId = ref(selectedPreset.id);

const presetNeedsUpdate = ref(false);

const initSelectedPreset = () => {
    const preset = localStorage.getItem('selected-preset');

    if (preset) {
        changePreset(Number.parseInt(preset));
    }
}

const form = useForm({
    collection_id: null,
    model: 'gpt-4-vision-preview',
    system_prompt: '',
    user_prompt: '',
    name: null,
    temperature: 1,
    top_p: 1,
    presence_penalty: 0,
    frequency_penalty: 0,
    input_language_id: null,
    output_language_id: null,
});

watch(() => selectedPresetId.value, (value, oldValue) => {
    presetNeedsUpdate.value = false;
})

watch(() => form.data(),
    (value, oldValue) => presetNeedsUpdate.value = true,
    { deep: true }
);

onMounted(() => { presetNeedsUpdate.value = false; initSelectedPreset(); } )

const modelOptions = () => {
    const models = [];

    props.models.forEach((el) => {
        models.push({value: el,label: el});
    })

    return models;
}

const addingPreset = ref(!props.presets.length);
const showMainPanel = computed(() => !!selectedPreset.value && !addingPreset.value)

const presetOptions = () => {
    const presets = [];

    props.presets.forEach((el) => {
        presets.push({value: el.id,label: el.name});
    })

    return presets;
}

const changePreset = (value, showNotifications = true) => {
    const preset = isObject(value)
        ? value
        : getPreset(value)

    selectedPreset.value = preset ? preset : null;
    selectedPresetId.value = preset ? preset.id : null;

    if (preset) {
        addingPreset.value = false;
        localStorage.setItem('selected-preset', preset.id);
        fillPresetForm(preset);
    }

    presetNeedsUpdate.value = false;
}

const getPreset = (presetId) => {
    if (!presetId) {
        return null;
    }

    return props.presets.find((el) => el.id === presetId);
}

const fillPresetForm = (preset) => {
    Object.getOwnPropertyNames(preset).forEach((property) => {
        if (form.hasOwnProperty(property)) {
            form[property] = preset[property];
        }
    });
}

const addPreset = () => {
    addingPreset.value = true;
    resetForm();
}

const resetForm = () => {
    form.defaults({model: 'gpt-3.5-turbo', system_prompt: '', user_prompt: '', name: null, temperature: 1, top_p: 1, presence_penalty: 0, frequency_penalty: 0, input_language_id: null, output_language_id: null});
    form.reset();
}

const cancelPreset = () => {
    addingPreset.value = false;
    changePreset(selectedPreset.value);
}

const savePreset = () => {
    form.collection_id = usePage().props.auth.user.current_collection.id;

    if (addingPreset.value) {
        createPreset();
    } else if (selectedPreset.value) {
        updatePreset();
    }
}

const notifyValidationError = (error) => {
    return trans(error[Object.keys(error)[0]]) ?? trans("Something wrong happens.");
}

const createPreset = () => {
    form.post(route('presets.store'), {
        errorBag: 'errors',
        preserveScroll: true,
        onSuccess: () => {
            changePreset(props.selectedPreset)
            notify({
                group: "success",
                title: trans("Success"),
                text: trans("Preset created!")
            }, 4000)
        },
        onError: (error) => {
            notify({
                group: "error",
                title: trans("Error"),
                text: notifyValidationError(error)
            }, 4000)
        }
    })
}

const updatePreset = (onSuccess) => {
    form.patch(route('presets.update', selectedPreset.value), {
        errorBag: 'errors',
        preserveScroll: true,
        onSuccess: () => {
            changePreset(selectedPreset.value.id, false);

            if (onSuccess) {
                onSuccess();
            }

            notify({
                group: "success",
                title: trans("Success"),
                text: trans("Preset updated!")
            }, 4000);
        },
        onError: (error) => {
            notify({
                group: "error",
                title: trans("Error"),
                text: notifyValidationError(error)
            }, 4000)
        }
    })
};

const renamePreset = (value) => {
    form.name = value;
    updatePreset();
}

const deletePreset = () => {
    form.delete(route('presets.destroy', selectedPreset.value), {
        errorBag: 'errors',
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            changePreset(null);
            resetForm();
            notify({
                group: "success",
                title: trans("Success"),
                text: trans("Preset deleted!")
            }, 4000)
        },
    })
}

const changeInputLanguage = (language) => {
    form.input_language_id = language ? language.id : null;
}

const changeOutputLanguage = (language) => {
    form.output_language_id = language ? language.id : null;
}

</script>

<template>
    <AppLayout title="Editor">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('Editor') }}
            </h2>
        </template>

        <DashboardPanel>
            <EmptyCollection v-if="!$page.props.auth.user.current_collection" />

            <EmptyImport v-else-if="!hasItems" />

            <template v-else>
                <div class="flex border-b border-gray-200 pb-8 md:items-center">
                    <div class="md:items-center flex flex-1 flex-col md:flex-row">
                        <template v-if="addingPreset">
                            <template v-if="permissions.canManagePresets">
                                <div>
                                    <label class="mr-2 font-medium">Name:</label>
                                    <TextInput v-model="form.name" type="text" class="w-60"/>

                                    <PrimaryButton @click="savePreset" :disabled="form.processing" :class="{ 'opacity-50': form.processing }" class="ml-2 gap-x-1.5">
                                        {{$t('Save')}}
                                        <ArrowDownTrayIcon class="-mr-0.5 w-4" aria-hidden="true" />
                                    </PrimaryButton>

                                    <SecondaryButton v-if="presets.length" class="ml-2 gap-x-1.5" @click="cancelPreset">
                                        {{$t('Cancel')}}
                                        <XCircleIcon class="-mr-0.5 w-4" aria-hidden="true" />
                                    </SecondaryButton>
                                </div>
                            </template>
                        </template>

                        <template v-else>
                            <div class="flex">
                                <label class="mr-2 mt-1.5 font-medium">{{ $t('Preset') }}:</label>
                                <SelectMenu @update:modelValue="changePreset" v-model="selectedPresetId" :options="presetOptions()" class="w-60" :placeholder="trans('Select')" />
                            </div>

                            <template v-if="permissions.canManagePresets">
                                <div class="flex justify-center sm:hidden ml-1 sm:ml-5 mt-2 text-gray-500 text-sm" v-if="selectedPresetId">
                                    ID: {{selectedPresetId}}
                                </div>
                                <div class="flex mt-5 md:mt-0">

                                    <span>
                                        <PrimaryButton @click="addPreset" class="ml-1 sm:ml-2 sm:gap-x-1.5">
                                            {{ $t('Add') }}
                                            <PlusCircleIcon class="-mr-0.5 w-4" aria-hidden="true" />
                                        </PrimaryButton>
                                    </span>
                                    <span>
                                        <PrimaryButton v-if="selectedPreset"
                                                       :class="{ 'opacity-50': form.processing }"
                                                       :disabled="form.processing"
                                                       @click="savePreset"
                                                       class="ml-1 sm:ml-2 sm:gap-x-1.5">
                                        {{$t('Save')}}
                                            <ArrowDownTrayIcon class="-mr-0.5 w-4" aria-hidden="true" />
                                        </PrimaryButton>
                                    </span>



                                    <RenamePreset v-if="selectedPreset" :name="form.name" @rename="renamePreset">
                                        <PrimaryButton class="ml-1 sm:ml-2 sm:gap-x-1.5">
                                            {{$t('Rename')}}
                                            <PencilSquareIcon  class="-mr-0.5 w-4" aria-hidden="true" />
                                        </PrimaryButton>
                                    </RenamePreset>

                                    <DeletePreset @delete="deletePreset" v-if="selectedPreset" :name="form.name">
                                        <DangerButton class="ml-1 sm:ml-2 sm:gap-x-1.5">
                                            {{$t('Delete')}}
                                            <MinusCircleIcon class="-mr-0.5 w-4" aria-hidden="true" />
                                        </DangerButton>
                                    </DeletePreset>
                                    <div class="hidden sm:block ml-5 mt-1 text-gray-500 w-max" v-if="selectedPresetId">
                                        ID: {{selectedPresetId}}
                                    </div>
                                </div>

                            </template>
                        </template>
                    </div>
                </div>

                <div v-if="!showMainPanel" class="text-center mt-5 text-gray-700">
                <span>
                    {{$t('Select or create a new preset...')}}
                </span>
                </div>

                <template v-if="showMainPanel">
                    <section v-if="permissions.canChangeOpenAIParams" aria-labelledby="filter-heading" class="pt-8">
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:flex items-center justify-between sm:space-x-6">
                            <div class="col-span-2">
                                <SelectMenu :disabled="!permissions.canManagePresets" v-model="form.model" :options="modelOptions()" class="ml-5 mr-8 mb-5 md:ml-0 md:mr-0 md:mb-0 md:min-w-44 md:inline-block" :placeholder="trans('Select a model')" />
                            </div>

                            <div class="w-23 sm:w-56 mr-2 sm:mr-0">
                                <RangeSlider :disabled="!permissions.canManagePresets" v-model="form.temperature" :min="0" :max="2" :step="0.01">
                                    <template #label>
                                        <label class="inline-flex text-sm font-medium"> {{ $t('Temperature') }} </label>
                                    </template>
                                </RangeSlider>
                            </div>

                            <div class="w-23 sm:w-56 mr-2 sm:mr-0">
                                <RangeSlider :disabled="!permissions.canManagePresets" v-model="form.top_p" :min="0" :max="1" :step="0.01">
                                    <template #label>
                                        <label class="inline-flex text-sm font-medium"> {{ $t('Top p') }} </label>
                                    </template>
                                </RangeSlider>
                            </div>

                            <div class="w-23 sm:w-56 mr-2 sm:mr-0">
                                <RangeSlider :disabled="!permissions.canManagePresets" v-model="form.presence_penalty" :min="-2" :max="2" :step="0.01">
                                    <template #label>
                                        <label class="inline-flex text-sm font-medium"> {{ $t("Presence Penalty") }} </label>
                                    </template>
                                </RangeSlider>
                            </div>

                            <div class="w-23 sm:w-56 mr-2 sm:mr-0">
                                <RangeSlider :disabled="!permissions.canManagePresets" v-model="form.frequency_penalty" :min="-2" :max="2" :step="0.01">
                                    <template #label>
                                        <label class="inline-flex text-sm font-medium"> {{$t('Frequency Penalty')}} </label>
                                    </template>
                                </RangeSlider>
                            </div>
                        </div>
                    </section>

                    <!-- Prompt fields -->
                    <div class="lg:grid pt-8">

                        <div class="mt-6 lg:mt-0 bg-gray-50 rounded p-4">
                            <PromptEditor :canEdit="permissions.canManagePresets" :title="$t('User')" v-model="form.user_prompt" />
                        </div>
                    </div>

                    <ItemCompletionPreview @update:inputLanguage="changeInputLanguage"
                                           @update:outputLanguage="changeOutputLanguage"
                                           :preset="selectedPreset"
                                           :languages="languages"
                                           :canChangeLanguage="permissions.canManagePresets"
                                           :needPresetUpdate="presetNeedsUpdate"
                                           :updatePreset="updatePreset"/>
                </template>
            </template>
        </DashboardPanel>
    </AppLayout>
</template>

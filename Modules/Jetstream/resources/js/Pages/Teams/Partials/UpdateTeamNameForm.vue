<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from 'Jetstream/Components/ActionMessage.vue';
import FormSection from 'Jetstream/Components/FormSection.vue';
import InputError from 'Jetstream/Components/InputError.vue';
import InputLabel from 'Jetstream/Components/InputLabel.vue';
import PrimaryButton from 'Jetstream/Components/PrimaryButton.vue';
import TextInput from 'Jetstream/Components/TextInput.vue';

const props = defineProps({
    team: Object,
    permissions: Object,
});

const form = useForm({
    name: props.team.name,
});

const updateTeamName = () => {
    form.put(route('teams.update', props.team), {
        errorBag: 'updateTeamName',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateTeamName">
        <template #title>
            {{ $t('Team Name') }}
        </template>

        <template #description>
            {{$t("The team's name and owner information.")}}
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <InputLabel value="Team Owner" />

                <div class="flex items-center mt-2">
                    <div class="leading-tight">
                        <div class="text-gray-900 dark:text-white">{{ team.owner.name }}</div>
                        <div class="text-gray-700 dark:text-gray-300 text-sm">
                            {{ team.owner.email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Team Name" />

                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    :disabled="!permissions.canUpdateTeam"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>
        </template>

        <template v-if="permissions.canUpdateTeam" #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                {{ $t('Saved.') }}
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ $t('Save') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>

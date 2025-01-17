<script setup>
import {computed, ref} from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import Banner from 'Jetstream/Components/Banner.vue';
import Dropdown from 'Jetstream/Components/Dropdown.vue';
import DropdownLink from 'Jetstream/Components/DropdownLink.vue';
import NavLink from 'Jetstream/Components/NavLink.vue';
import ResponsiveNavLink from 'Jetstream/Components/ResponsiveNavLink.vue';
import SubscriptionBanner from 'Modules/Subscriptions/resources/js/Components/SubscriptionBanner.vue';
import NotificationBanner from 'Jetstream/Components/NotificationBanner.vue';
import ApplicationLogo from 'Jetstream/Components/ApplicationLogo.vue';
import LanguageSelector from "Jetstream/Components/LanguageSelector.vue";

defineProps({
    title: String,
    locale: String
});

const locale = localStorage.getItem('locale') || 'en';

const showingNavigationDropdown = ref(false);

const currentTeam = computed(() => usePage().props.auth.user.current_team);
const currentCollection = computed( () => usePage().props.auth.user.current_collection);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const switchToCollection = (collection) => {
    localStorage.removeItem('selected-preset');
    router.put(route('current-collection.update', collection.id), {}, {
        preserveState: false,
    });
}

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <NotificationBanner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <SubscriptionBanner v-if="$page.props.planSubscription" :planSubscription="$page.props.planSubscription" />

            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto" />
                                </Link>
                            </div>
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :class="route().current('dashboard') ? 'dark:text-white' : ''" :active="route().current('dashboard')">
                                    {{$t('Dashboard')}}
                                </NavLink>
                            </div>
                            <div v-show="$page.props.auth.user.is_admin" class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('teams.index')" :class="route().current('teams.index') ? 'dark:text-white' : ''" :active="route().current('teams.index')">
                                    {{$t('Teams')}}
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('import.index')" :class="route().current('import.index') ? 'dark:text-white' : ''" :active="route().current('import.index')">
                                    {{$t('Import')}}
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('editor.index')" :class="route().current('editor.index') ? 'dark:text-white' : ''" :active="route().current('editor.index')">
                                    {{$t('Editor')}}
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('compilations.index')" :class="route().current('compilations.index') ? 'dark:text-white' : ''" :active="route().current('compilations.index')">
                                    {{$t('Compilations')}}
                                </NavLink>
                            </div>


                            <div class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('export.index')" :class="route().current('export.index') ? 'dark:text-white' : ''" :active="route().current('export.index')">
                                    {{$t('Export')}}
                                </NavLink>
                            </div>
                            <div v-if="$page.props.jetstream.hasApiFeatures && $page.props.canUseApiFeatures" class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                                <NavLink :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                    {{$t('API')}}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden md:flex md:items-center md:ml-6">
                            <!-- Collection Dropdown -->
                            <div class="xl:ml-3 relative">
                                <Dropdown align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" :title="currentCollection ? currentCollection.name : $t('Collections')" class="max-2-lines inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ currentCollection ? currentCollection.name : $t('Collections') }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4 min-w-4 self-center" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <!-- Collection Management -->
                                        <template v-if="$page.props.jetstream.hasTeamFeatures">
                                            <template v-if="currentCollection && $page.props.collections.canViewCollection">
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{ $t('Manage Collections') }}
                                                </div>

                                                <!-- Collection Settings -->
                                                <DropdownLink :href="route('collections.show', currentCollection)">
                                                    {{ $t('Collection Settings') }}
                                                </DropdownLink>
                                            </template>

                                            <DropdownLink v-if="$page.props.collections.canCreateCollection" :href="route('collections.create')">
                                                {{$t('Create New Collection')}}
                                            </DropdownLink>

                                            <template v-if="$page.props.auth.user.current_team.collections.length">
                                                <div class="border-t border-gray-200 dark:border-gray-600" />

                                                <!-- Collection Switcher -->
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    {{$t('Switch Collections')}}
                                                </div>

                                                <template v-for="collection in $page.props.auth.user.current_team.collections" :key="collection.id">
                                                    <form @submit.prevent="switchToCollection(collection)">
                                                        <DropdownLink as="button">
                                                            <div class="flex items-center">
                                                                <div class="mr-2 h-5 w-5 min-w-5">
                                                                    <svg v-if="collection.id === $page.props.auth.user.current_collection_id" class="text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                </div>
                                                                <div>{{ collection.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </template>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="xl:ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.first_name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.first_name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4 min-w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{$t('Manage Account')}}
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            {{$t('Profile')}}
                                        </DropdownLink>

                                        <DropdownLink :href="route('teams.show', currentTeam)">
                                            {{$t('Team Settings')}}
                                        </DropdownLink>

                                        <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                            {{$t('Create New Team')}}
                                        </DropdownLink>

                                        <!-- Team Switch -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{$t('Switch Team')}}
                                        </div>

                                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                            <form @submit.prevent="switchToTeam(team)">
                                                <DropdownLink as="button">
                                                    <div class="flex items-center">
                                                        <div class="mr-2 h-5 w-5 min-w-5">
                                                            <svg v-if="team.id === $page.props.auth.user.current_team_id" class="text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>{{ team.name }}</div>
                                                    </div>
                                                </DropdownLink>
                                            </form>
                                        </template>

                                        <template v-if="$page.props.auth.user.is_admin">
                                            <div class="border-t border-gray-200 dark:border-gray-600" />

                                            <DropdownLink :href="route('app.settings.index')">
                                                {{$t('App Settings')}}
                                            </DropdownLink>
                                        </template>

                                        <div class="border-t border-gray-200 dark:border-gray-600" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                {{$t('Log Out')}}
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                            <LanguageSelector />

                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center md:hidden">
                            <div class="">
                                <LanguageSelector />
                            </div>
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="md:hidden">

                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            {{ $t('Dashboard') }}
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('import.index')" :active="route().current('import.index')">
                            {{ $t('Import') }}
                        </ResponsiveNavLink>
                    </div>
                    <div v-show="$page.props.auth.user.is_admin" class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('teams.index')" :active="route().current('teams.index')">
                            {{$t('Teams')}}
                        </ResponsiveNavLink>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-4 xl:ml-10 sm:flex">
                        <NavLink :href="route('import.index')" :class="route().current('import.index') ? 'dark:text-white' : ''" :active="route().current('import.index')">
                            {{$t('Import')}}
                        </NavLink>
                    </div>

                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('editor.index')" :active="route().current('editor.index')">
                            {{$t('Editor')}}
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('compilations.index')" :class="route().current('compilations.index') ? 'dark:text-white' : ''" :active="route().current('compilations.index')">
                            {{$t('Compilations')}}
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('export.index')" :active="route().current('export.index')">
                            {{$t('Export')}}
                        </ResponsiveNavLink>
                    </div>

                    <div v-if="$page.props.jetstream.hasApiFeatures && $page.props.canUseApiFeatures" class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                            API
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.first_name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {{ $page.props.auth.user.first_name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                {{ $t('Profile') }}
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.auth.user.is_admin" :href="route('app.settings.index')" :active="route().current('app.settings.index')">
                                {{$t('App Settings')}}
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    {{ $t('Log Out') }}
                                </ResponsiveNavLink>
                            </form>

                            <!-- Collection Management -->
                            <div>
                                <div class="border-t border-gray-200 dark:border-gray-600" />

                                <template v-if="currentCollection && $page.props.collections.canViewCollection">
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ $t('Manage Collection') }}
                                    </div>

                                    <!-- Collection Settings -->
                                    <ResponsiveNavLink :href="route('collections.show', currentCollection)" :active="route().current('collections.show')">
                                        {{ $t('Collection Settings') }}
                                    </ResponsiveNavLink>
                                </template>

                                <ResponsiveNavLink v-if="$page.props.collections.canCreateCollection" :href="route('collections.create')" :active="route().current('collections.create')">
                                    {{ $t('Create New Collection') }}
                                </ResponsiveNavLink>

                                <template v-if="$page.props.auth.user.current_team.collections.length">
                                    <div class="border-t border-gray-200 dark:border-gray-600" />

                                    <!-- Collection Switcher -->
                                    <div  class="block px-4 py-2 text-xs text-gray-400">
                                        {{ $t('Switch Collections') }}
                                    </div>

                                    <template v-for="collection in $page.props.auth.user.current_team.collections" :key="collection.id">
                                        <form @submit.prevent="switchToCollection(collection)">
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <div class="mr-2 h-5 w-5 min-w-5">
                                                        <svg v-if="collection.id === $page.props.auth.user.current_collection_id" class="text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>{{ collection.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </div>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200 dark:border-gray-600" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{$t('Manage Team')}}
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink :href="route('teams.show', currentTeam)" :active="route().current('teams.show')">
                                    {{$t('Team Settings')}}
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                    {{$t('Create New Team')}}
                                </ResponsiveNavLink>

                                <div class="border-t border-gray-200 dark:border-gray-600" />

                                <!-- Team Switcher -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{$t('Switch Teams')}}
                                </div>

                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <ResponsiveNavLink as="button">
                                            <div class="flex items-center">
                                                <div class="mr-2 h-5 w-5 min-w-5">
                                                    <svg v-if="team.id === currentTeam.id" class="text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </ResponsiveNavLink>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
.max-2-lines {
    padding: 0;
    max-width: 12rem;
    max-height: 49px;
    align-items: flex-start;
    justify-content: flex-end;
    text-overflow: ellipsis;
    word-wrap: break-word;
    overflow: hidden;
    border: 8px solid transparent;
}
</style>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import Sidebar from '@/Components/Sidebar.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    auth: {
        type: Object,
        default: () => ({
            user: null
        }),
    },
    title: {
        type: String,
        default: '',
    },
});

</script>

<template>
    <div>
        <Head :title="title" />

        <div class="flex flex-col min-h-screen bg-gray-100">
            <!-- Navigation Bar - Updated to match image -->
            <nav class="bg-white border-b border-gray-200 shadow-sm"  >
                <div class="flex items-center justify-between h-16 px-6" style="    background: #0b431b;">
                    <!-- Left side - Logo and Dashboard link -->
                    <div class="flex items-center space-x-4">
                        <!-- <Link :href="route('dashboard')" class="flex items-center">
                            <ApplicationLogo class="block w-auto h-8" />
                            <span class="ml-3 text-xl font-semibold text-gray-800">Dashboard</span>
                        </Link> -->
                            <span class="ml-3 text-xl font-semibold text-white">Induction</span>
                    </div>

                    <!-- Right side - User dropdown -->
                    <div v-if="auth?.user" class="flex items-center space-x-4">
                        <!-- Admin Panel link if user has permission -->
                        <NavLink v-if="auth?.user?.can?.['users.manage']"
                                :href="route('admin.dashboard')"
                                :active="route().current('admin.dashboard')"
                                class="px-3 py-1 text-sm font-medium text-white-700 hover:text-white-900">
                            Admin Panel
                        </NavLink>

                        <!-- User dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center space-x-2 focus:outline-none">
                                    <span class="text-sm font-medium font-bold text-white">{{ auth.user.name }}</span>
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-full">
                                        <span class="text-sm font-medium text-white">
                                            {{ auth.user.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    My Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </nav>

            <!-- Main Content with Sidebar -->
            <div class="flex flex-1">
                <!-- Sidebar - Only show if user exists -->
                <Sidebar v-if="auth?.user" :user="auth.user" />

                <!-- Page Content -->
                <div class="flex-1 overflow-auto">
                    <main class="container px-4 py-6 mx-auto">
                        <slot />
                    </main>

                    <!-- Gr Code -->
                    <Footer />
                </div>
            </div>
        </div>
    </div>
</template>

<template>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Add New Induction Phase</h2>
                <Link :href="route('InductionPhase.index')"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
                </Link>
            </div>

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div v-if="form.errors.duplicate" class="p-4 text-sm text-red-600 bg-red-50 rounded-md">
                            {{ form.errors.duplicate }}
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Phase Title</label>
                                <input v-model="form.title" id="title" type="text" required
                                    placeholder="Enter phase title (e.g. Initial Screening)"
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
                                    :class="{ 'border-red-500': form.errors.title }">
                                <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select v-model="form.status" id="status"
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
                                    :class="{ 'border-red-500': form.errors.status }">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input v-model="form.start_date" id="start_date" type="date" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
                                    :class="{ 'border-red-500': form.errors.start_date }">
                                <p v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">{{ form.errors.start_date }}</p>
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input v-model="form.end_date" id="end_date" type="date" required
                                    class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
                                    :class="{ 'border-red-500': form.errors.end_date }">
                                <p v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">{{ form.errors.end_date }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="reset"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]">
                                Reset
                            </button>
                            <button type="submit"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-[#0e5723] border border-transparent rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
                                :disabled="form.processing">
                                <svg v-if="form.processing" class="w-5 h-5 mr-2 -ml-1 animate-spin"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                {{ form.processing ? 'Saving...' : 'Save Phase' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const form = useForm({
    title: '',
    status: 'Active',
    start_date: '',
    end_date: ''
});

const submit = () => {
    form.post(route('InductionPhase.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const reset = () => {
    form.reset();
};
</script>

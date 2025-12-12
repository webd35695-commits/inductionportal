<template>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Edit Qualification Type</h2>
                <Link
                    :href="route('qualification-types.index')"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                    Back to Qualification Types
                </Link>
            </div>

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                                    {{ errors.name }}
                                </p>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <p v-if="errors.status" class="mt-2 text-sm text-red-600">
                                    {{ errors.status }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
                                :disabled="processing"
                            >
                                <span v-if="processing">Updating...</span>
                                <span v-else>Update Qualification Type</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    qualificationType: Object,
    errors: Object,
});

const form = useForm({
    name: props.qualificationType.name,
    status: props.qualificationType.status,
});

const processing = ref(false);

const submit = () => {
    processing.value = true;
    form.put(route('qualification-types.update', props.qualificationType.id), {
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

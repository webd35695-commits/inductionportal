<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    province: Object,
});
</script>

<template>
    <Head title="Province Details" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Province Details</h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p><strong>ID:</strong> {{ province.id }}</p>
                        <p><strong>Name:</strong> {{ province.name }}</p>
                        <p><strong>Status:</strong> {{ province.status }}</p>
                        <Link :href="route('provinces.index')" class="inline-block px-4 py-2 mt-4 text-white bg-blue-500 rounded">Back</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

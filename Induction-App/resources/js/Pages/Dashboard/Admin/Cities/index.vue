<template>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">City Management</h2>
                <Link  :href="route('cities.create')"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723] group">
                <svg class="w-5 h-5 mr-2 text-white group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add City
                </Link>
            </div>
            <SuccessPopup v-if="showDeleteSuccess && $page.props.flash.message"
                 :message="$page.props.flash.message" />
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <table id="cityTable" class="w-full display responsive nowrap">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">
                                    District</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">
                                    City</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="city in cities.data" :key="city.id" class="hover:bg-gray-50">
                                <td
                                    class="px-6 py-4 text-sm font-medium text-purple-600 border-b border-gray-200 whitespace-nowrap">
                                    #{{ city.id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 border-b border-gray-200 whitespace-nowrap">
                                    {{ city.district }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 border-b border-gray-200 whitespace-nowrap">
                                    {{ city.name }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium border-b border-gray-200 whitespace-nowrap">
                                    <div class="flex space-x-3">
                                        <Link :href="route('cities.edit', city.id)"
                                            class=" p-1 text-green-600 rounded-md hover:text-green-900 hover:bg-green-50"
                                            title="Edit">
                                        <svg class="edit w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        </Link>
                                        <button @click="deleteCity(city.id)"
                                            class=" p-1 text-red-600 rounded-md hover:text-red-900 hover:bg-red-50"
                                            title="Delete">
                                            <svg class="delete w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Footer from '@/Components/Footer.vue';
import SuccessPopup from '@/Components/SuccessPopup.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    cities: Object,
    can: Object
});

const showDeleteSuccess = ref(false);

const deleteCity = (id) => {
    if (confirm('Are you sure you want to delete this city?')) {
        router.delete(route('cities.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteSuccess.value = true;
                setTimeout(() => showDeleteSuccess.value = false, 3000);
            },
        });
    }
};

onMounted(() => {
    $('#cityTable').DataTable({
        responsive: true,
        dom: '<"flex flex-col md:flex-row md:items-center md:justify-between p-4"<"mb-4 md:mb-0"l><"md:flex md:space-x-4"f>>rt<"flex flex-col md:flex-row md:items-center md:justify-between p-4"<"text-sm text-gray-600 mb-4 md:mb-0"i><"pagination flex space-x-2"p>>',
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        language: {
            search: "",
            searchPlaceholder: "Search cities...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        initComplete: function () {
            $('.dataTables_filter input').addClass('block w-full px-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm');
            $('.dataTables_length select').addClass('block w-full px-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm');
        },
        drawCallback: function () {
            $('.paginate_button').addClass('px-3 py-1 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50');
            $('.paginate_button.current').addClass('bg-indigo-50 border-indigo-500 text-indigo-600 hover:bg-indigo-50');
            $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');
        }
    });
});
</script>

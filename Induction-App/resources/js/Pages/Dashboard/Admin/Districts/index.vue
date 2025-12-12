<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import SuccessPopup from '@/Components/SuccessPopup.vue';
import AddDistrictModal from '@/Components/AddDistrictModal.vue';
import EditDistrictModal from '@/Components/EditDistrictModal.vue';
import { ref } from 'vue';

defineOptions({
  layout: AuthenticatedLayout,
});

const { props } = usePage();
const districts = ref(props.districts || []);
const provinces = ref(props.provinces || []);
const showModal = ref(false); // Control add modal visibility
const editModal = ref(false); // Control edit modal visibility
const selectedDistrict = ref(null); // Store the district being edited
// Function to open edit modal
const openEditModal = (district) => {
  selectedDistrict.value = district;
  editModal.value = true;
};
// Function to handle district deletion
const deleteDistricts = (id) => {
  if (confirm('Are you sure you want to delete this district?')) {
    router.delete(route('districts.destroy', id));
  }
};
</script>

<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Districts Management</h2>
        <button
          @click="showModal = true"
          class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723] group"
        >
          <svg class="w-5 h-5 mr-2 text-white group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add District
        </button>
      </div>

      <!-- Success Popup for create, update, and delete -->
      <SuccessPopup v-if="props.flash.message || props.flash.error" :message="props.flash.message || props.flash.error" />

      <!-- Add District Modal -->
      <AddDistrictModal
        :isOpen="showModal"
        :provinces="provinces"
        @close="showModal = false"
      />

      <!-- Edit District Modal -->
      <EditDistrictModal
        :isOpen="editModal"
        :district="selectedDistrict"
        :provinces="provinces"
        @close="editModal = false"
      />

      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
          <table id="districtTable" class="w-full display responsive nowrap">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">ID</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">Province Name</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">District Name</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">Status</th>
                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border-b border-gray-200">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(district, index) in districts" :key="district.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-purple-600 border-b border-gray-200 whitespace-nowrap">
                  #{{ index + 1 }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900 border-b border-gray-200 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="ml-4">
                      <div class="font-medium text-gray-900">{{ district.province.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900 border-b border-gray-200 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="ml-4">
                      <div class="font-medium text-gray-900">{{ district.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 whitespace-nowrap">
                  <span
                    :class="{
                      'bg-green-100 text-green-800': district.status === 'Active',
                      'bg-red-100 text-red-800': district.status === 'Inactive',
                    }"
                    class="inline-flex px-3 py-1 text-xs font-semibold leading-5 rounded-full"
                  >
                    {{ district.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm font-medium border-b border-gray-200 whitespace-nowrap">
                  <div class="flex space-x-3">
                    <Link 
                      :href="route('districts.show', district.id)"
                      class="p-1 text-indigo-600 rounded-md hover:text-indigo-900 hover:bg-indigo-50"
                      title="View"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </Link>
                    <button
                      @click="openEditModal(district)"
                      class="p-1 text-green-600 rounded-md hover:text-green-900 hover:bg-green-50"
                      title="Edit"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteDistricts(district.id)"
                      class="p-1 text-red-600 rounded-md hover:text-red-900 hover:bg-red-50"
                      title="Delete"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
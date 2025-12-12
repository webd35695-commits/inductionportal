<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  isOpen: Boolean,
  district: Object,
  provinces: Array,
});

const emit = defineEmits(['close']);

const form = ref({
  name: '',
  province_id: '',
  status: 'Active',
});

// Populate form when district prop changes
watch(
  () => props.district,
  (newDistrict) => {
    if (newDistrict) {
      form.value.name = newDistrict.name || '';
      form.value.province_id = newDistrict.province_id || '';
      form.value.status = newDistrict.status || 'Active';
    }
  },
  { immediate: true }
);

const submit = () => {
  router.put(route('districts.update', props.district.id), form.value, {
    onSuccess: () => {
      emit('close');
    },
  });
};

const closeModal = () => {
  emit('close');
};
</script>

<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit District</h3>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label
            for="name"
            class="block text-sm font-medium text-gray-700"
          >
            District Name
          </label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#0e5723] focus:ring-[#0e5723] sm:text-sm"
            required
          />
        </div>
        <div class="mb-4">
          <label
            for="province_id"
            class="block text-sm font-medium text-gray-700"
          >
            Province
          </label>
          <select
            v-model="form.province_id"
            id="province_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#0e5723] focus:ring-[#0e5723] sm:text-sm"
            required
          >
            <option value="">Select Province</option>
            <option
              v-for="province in provinces"
              :key="province.id"
              :value="province.id"
            >
              {{ province.name }}
            </option>
          </select>
        </div>
        <div class="mb-4">
          <label
            for="status"
            class="block text-sm font-medium text-gray-700"
          >
            Status
          </label>
          <select
            v-model="form.status"
            id="status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#0e5723] focus:ring-[#0e5723] sm:text-sm"
          >
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="closeModal"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
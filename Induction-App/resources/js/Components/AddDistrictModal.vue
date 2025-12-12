<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Add New District</h2>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="province_id" class="block text-sm font-medium text-gray-700">Province</label>
          <select
            v-model="form.province_id"
            id="province_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
            required
          >
            <option value="" disabled>Select a province</option>
            <option v-for="province in provinces" :key="province.id" :value="province.id">
              {{ province.name }}
            </option>
          </select>
          <p v-if="form.errors.province_id" class="text-red-500 text-sm mt-1">{{ form.errors.province_id }}</p>
        </div>
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">District Name</label>
          <input
            v-model="form.name"
            id="name"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
            required
          />
          <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
        </div>
        <div class="mb-4">
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select
            v-model="form.status"
            id="status"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
            required
          >
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
          <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</p>
        </div>
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md hover:bg-[#1B2850]"
            :disabled="form.processing"
          >
            Save District
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
  isOpen: Boolean,
  provinces: Array,
});

const emit = defineEmits(['close']); // Store the emit function in a variable

const form = useForm({
  province_id: '',
  name: '',
  status: 'Active',
});

const submit = () => {
  form.clearErrors();
  console.log('Submitting form:', form);
  form.post(route('districts.store'), {
    onSuccess: () => {
      form.reset();
      emit('close'); // Use the emit variable to emit the 'close' event
    },
  });
};
</script>
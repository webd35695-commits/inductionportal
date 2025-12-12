```vue
<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
    @click.self="closeModal"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Add Post Level</h3>
        <button
          @click="closeModal"
          class="text-gray-500 hover:text-gray-700 focus:outline-none"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label
            for="name"
            class="block text-sm font-medium text-gray-700"
          >
            Post Level Name
          </label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            :class="{ 'border-red-500': form.errors.name }"
            placeholder="Enter post level name"
          />
          <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
            {{ form.errors.name }}
          </p>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">
              Status
            </label>
            <select
              v-model="form.status"
              id="status"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                :class="{ 'border-red-500': form.errors.status }"
                >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <p v-if="form.errors.status" class="mt-1 text-sm text-red
-600">
              {{ form.errors.status }}
            </p>

        </div>

     

        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="closeModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
            :disabled="form.processing"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(['close']);

const form = useForm({
  name: '',
  status: '',
});

const submitForm = () => {
  form.post(route('post_levels.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      emit('close');
    },
  });
};

const closeModal = () => {
  form.reset();
  form.clearErrors();
  emit('close');
};
</script>
```
<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
      <h3 class="text-lg font-bold text-gray-800 mb-4">Apply for {{ post.name }} (BPS-{{ post.bps }})</h3>
      <form @submit.prevent="submitApplication">
        <div class="mb-4">
          <label for="testCity1" class="block text-sm font-medium text-gray-700">Preferred Test City 1</label>
          <select
            id="testCity1"
            v-model="form.testCity1"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
            required
          >
            <option value="" disabled>Select a city</option>
<option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>             </select>
        </div>
        <div class="mb-4">
          <label for="testCity2" class="block text-sm font-medium text-gray-700">Preferred Test City 2</label>
          <select
            id="testCity2"
            v-model="form.testCity2"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
            required
          >
            <option value="" disabled>Select a city</option>
<option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>   
       </select>
        </div>
        <div class="flex justify-end space-x-2">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
          >
            Close
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700"
          >
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  testCities: {
    type: Array,
    default: () => [],
  },
});
const emit = defineEmits(['close','applied']);

const cities = ref(props.testCities);

const form = ref({
  testCity1: '',
  testCity2: '',
});

const submitApplication = () => {
  router.post('/candidate/apply', {
    postId: props.post.id,
    testCity1: form.value.testCity1,
    testCity2: form.value.testCity2,
  }, {
    onSuccess: () => {
      form.value.testCity1 = '';
      form.value.testCity2 = '';
      // Refresh only the applications data on the dashboard
      router.reload({ only: ['myJobsApplication'] });
      emit('applied');
      emit('close');
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    },
  });
};
</script>

<style scoped>
.fixed {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
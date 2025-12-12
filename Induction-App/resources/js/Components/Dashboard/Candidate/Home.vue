<!-- resources/js/components/Home.vue -->
<template>
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Welcome to the Home Page</h2>
    <p class="text-gray-600">
      This is a sample Vue component rendered inside the AppLayout. You can customize this component
      to display your content, such as data fetched from your Laravel backend.
    </p>
    <button
      @click="fetchData"
      class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
    >
      Fetch Data
    </button>
    <p v-if="data" class="mt-4 text-gray-700">{{ data }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const data = ref(null);

const fetchData = async () => {
  try {
    const response = await axios.get('/api/sample');
    data.value = response.data.message;
  } catch (error) {
    console.error('Error fetching data:', error);
    data.value = 'Failed to fetch data';
  }
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
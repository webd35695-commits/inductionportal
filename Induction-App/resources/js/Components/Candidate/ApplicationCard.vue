<template>
  <div class="text-center bg-gray-50 p-6 rounded-lg border hover:shadow-md transition">
    <div class="bg-green-700 p-4 rounded-lg mb-4 mx-auto w-16 h-16 flex items-center justify-center">
      <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
        <path v-html="iconPath"></path>
      </svg>
    </div>
    <p class="text-3xl font-bold text-gray-900 mb-2">{{ count }}</p>
    <p class="text-sm text-gray-600 leading-tight">{{ title }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  count: {
    type: [Number, String],
    default: 0
  },
  iconPath: {
    type: String,
    required: true
  }
});
</script>


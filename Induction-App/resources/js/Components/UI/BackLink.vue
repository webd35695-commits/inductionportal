<template>
  <a :href="href" class="inline-flex items-center text-gray-700 hover:text-teal-700">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    <span class="font-medium">{{ text }}</span>
  </a>
</template>

<script setup>
const props = defineProps({
  href: { type: String, required: true },
  text: { type: String, default: 'Back' }
});
</script>

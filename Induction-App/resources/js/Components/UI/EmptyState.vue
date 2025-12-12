<template>
  <div class="text-center p-10 border rounded-lg bg-white">
    <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 mb-3">
      <i :class="icon" class="text-gray-500"></i>
    </div>
    <h3 class="text-gray-800 font-semibold">{{ title }}</h3>
    <p class="text-gray-600 text-sm mt-1">{{ message }}</p>
    <div class="mt-4">
      <slot />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: 'Nothing here yet' },
  message: { type: String, default: '' },
  icon: { type: String, default: 'fas fa-inbox' }
});
</script>

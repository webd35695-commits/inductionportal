<!-- resources/js/components/TabButton.vue -->
<template>
  <button
    @click="$emit('click')"
    :class="{
      'border-emerald-500 text-emerald-600': active,
      'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': !active,
    }"
    class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm"
  >
    <slot />
  </button>
</template>
<script setup>
defineProps({ active: Boolean });
</script>
<template>
  <span :class="classes"><slot /></span>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps({
  variant: { type: String, default: 'gray' }
});

const classes = computed(() => {
  const base = 'inline-flex items-center px-2 py-1 rounded text-xs font-semibold';
  const map = {
    gray: 'bg-gray-100 text-gray-800',
    teal: 'bg-teal-100 text-teal-800',
    yellow: 'bg-yellow-100 text-yellow-800',
    red: 'bg-red-100 text-red-800',
    blue: 'bg-blue-100 text-blue-800'
  };
  return `${base} ${map[props.variant] || map.gray}`;
});
</script>

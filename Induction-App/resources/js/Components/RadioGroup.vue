<template>
  <div>
    <label class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <div class="mt-1 space-y-2">
      <div v-for="option in options" :key="option.value" class="flex items-center">
        <input
          :id="option.value"
          :value="option.value"
          v-model="selectedValue"
          type="radio"
          class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
        />
        <label :for="option.value" class="ml-3 block text-sm font-medium text-gray-700">{{ option.label }}</label>
      </div>
    </div>
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

defineProps({
  label: String,
  modelValue: String,
  options: Array,
  error: String
});

const selectedValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

defineEmits(['update:modelValue']);
</script>
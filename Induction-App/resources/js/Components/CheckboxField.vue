<template>
  <div class="flex items-center justify-between">
    <label class="block text-sm font-medium text-gray-700 flex-1">{{ label }}</label>
    <input
      type="checkbox"
      :checked="modelValue"
      @change="$emit('update:modelValue', $event.target.checked); $emit('change')"
      class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
    />
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  modelValue: Boolean,
  error: String
});

defineEmits(['update:modelValue', 'change']);
</script>
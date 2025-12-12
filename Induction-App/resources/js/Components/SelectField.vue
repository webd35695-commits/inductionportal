<template>
  <div>
    <label class="block text-sm font-medium text-gray-700" :for="id">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value); $emit('change')"
      :disabled="disabled"
      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
    >
      <option v-for="option in options" :key="option.value || option.id" :value="option.value || option.id">{{ option.label || option.name }}</option>
    </select>
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  modelValue: [String, Number],
  options: Array,
  disabled: Boolean,
  error: String,
  required: Boolean
});

defineEmits(['update:modelValue', 'change']);
</script>
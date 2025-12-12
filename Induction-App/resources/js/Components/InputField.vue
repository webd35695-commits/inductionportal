<template>
  <div>
    <label class="block text-sm font-medium text-gray-700" :for="id">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :type="type"
      :id="id"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      :maxlength="maxlength"
      :disabled="disabled"
      :min="min"
      :max="max"
      :class="['mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm', disabled ? 'bg-gray-100' : '']"
    />
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  type: { type: String, default: 'text' },
  modelValue: [String, Number],
  placeholder: String,
  maxlength: String,
  disabled: Boolean,
  error: String,
  required: Boolean,
  min: [String, Number],
  max: [String, Number]
});

defineEmits(['update:modelValue']);
defineOptions({ inheritAttrs: false });
</script>
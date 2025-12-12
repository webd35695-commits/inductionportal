<template>
  <transition name="fade">
    <div v-if="show" class="fixed top-4 right-4 z-50">
      <div :class="classes" class="px-4 py-3 rounded shadow">
        <slot>{{ message }}</slot>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
  message: { type: String, default: '' },
  type: { type: String, default: 'success' },
  duration: { type: Number, default: 3000 }
});

const show = ref(!!props.message);
watch(() => props.message, (val) => {
  show.value = !!val;
  if (show.value && props.duration) setTimeout(() => (show.value = false), props.duration);
});

const classes = computed(() => props.type === 'error'
  ? 'bg-red-600 text-white'
  : 'bg-teal-600 text-white');
</script>

<style>
.fade-enter-active, .fade-leave-active { transition: opacity .2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>

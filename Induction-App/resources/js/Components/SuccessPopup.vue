<template>
  <transition name="fade">
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full mx-4">
        <div class="text-center">
          <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          <h3 class="mt-2 text-lg font-medium text-gray-900">Alert!</h3>
          <div class="mt-2 text-sm text-gray-500">
            {{ message }}
          </div>
          <div class="mt-4">
            <button @click="close" type="button"
              class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]">
              OK
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  message: {
    type: String,
    default: 'Operation completed successfully'
  }
});

const show = ref(true);

const close = () => {
  show.value = false;
};
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

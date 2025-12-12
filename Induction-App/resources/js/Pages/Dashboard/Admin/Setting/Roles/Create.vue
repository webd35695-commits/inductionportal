<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Add New Role</h2>
        <Link
          :href="route('Roles.index')"
          class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5 mr-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 19l-7-7m0 0l7-7m-7 7h18"
            />
          </svg>
          Back to Main
        </Link>
      </div>

      <SuccessPopup
        v-if="showMessage && (flashMessage || flashError)"
        :message="flashMessage || flashError"
        :isError="!!flashError"
      />

      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
          <form @submit.prevent="submit" class="space-y-6">
            <div
              v-if="form.errors.duplicate"
              class="p-4 text-sm text-red-600 bg-red-50 rounded-md"
            >
              {{ form.errors.duplicate }}
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label
                  for="name"
                  class="block text-sm font-medium text-gray-700"
                  >Role Name</label
                >
                <input
                  v-model="form.name"
                  id="name"
                  type="text"
                  required
                  placeholder="Enter Role Name"
                  class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#0e5723] focus:border-[#0e5723] sm:text-sm"
                  :class="{ 'border-red-500': form.errors.name }"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                  {{ form.errors.name }}
                </p>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">
                Permissions
              </label>
              <p class="mt-1 text-sm text-gray-500">
                Select one or more permissions for this role
              </p>
              <div class="mt-4 space-y-4">
                <div v-for="permission in Permissions" :key="permission.id" class="flex items-start">
                  <div class="flex items-center h-5">
                    <input
                      :id="`permission-${permission.id}`"
                      v-model="form.permissions"
                      :value="permission.id"
                      type="checkbox"
                      class="w-4 h-4 text-[#0e5723] border-gray-300 rounded focus:ring-[#0e5723]"
                    />
                  </div>
                  <div class="ml-3 text-sm">
                    <label :for="`permission-${permission.id}`" class="font-medium text-gray-700">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
              </div>
              <p v-if="form.errors.permissions" class="mt-1 text-sm text-red-600">
                {{ form.errors.permissions }}
              </p>
            </div>

            <div class="flex justify-end space-x-3">
              <Link
                :href="route('Roles.index')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
              >
                Cancel
              </Link>
              <button
                type="submit"
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-[#0e5723] border border-transparent rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
                :disabled="form.processing"
              >
                <svg
                  v-if="form.processing"
                  class="w-5 h-5 mr-2 -ml-1 animate-spin"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                {{ form.processing ? "Saving..." : "Save Role" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SuccessPopup from "@/Components/SuccessPopup.vue";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  Permissions: {
    type: Array,
    default: () => []
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
});

const form = useForm({
  name: "",
  permissions: [],
});

const showMessage = ref(false);
const flashMessage = computed(() => props.flash.success || props.flash.message);
const flashError = computed(() => props.flash.error);

const submit = () => {
  form.post(route("Roles.store"), {
    onSuccess: () => {
      showMessage.value = true;
      setTimeout(() => {
        showMessage.value = false;
      }, 3000);
      form.reset();
    },
  });
};
</script>

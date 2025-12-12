<!-- resources/js/Pages/Applications/Show.vue -->
<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Application Details</h1>
    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Applicant Name</label>
            <p class="mt-1 text-sm text-gray-900">{{ application.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">CNIC</label>
            <p class="mt-1 text-sm text-gray-900">{{ application.cnic }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Mobile</label>
            <p class="mt-1 text-sm text-gray-900">{{ application.mobile || 'N/A' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Post</label>
            <p class="mt-1 text-sm text-gray-900">{{ application.post_name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <span :class="getStatusClasses(application.status)" class="mt-1 inline-block">
              {{ application.status.charAt(0).toUpperCase() + application.status.slice(1) }}
            </span>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Submission Date</label>
            <p class="mt-1 text-sm text-gray-900">{{ formatDate(application.date) }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <p class="mt-1 text-sm text-gray-900">{{ application.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Permanent Address</label>
            <p class="mt-1 text-sm text-gray-900">{{ application.permanent_address || 'N/A' }}</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Comments</label>
          <p class="mt-1 text-sm text-gray-900">{{ application.comments || 'No comments provided' }}</p>
        </div>
      </div>

      <div class="mt-6 flex justify-end space-x-3">
        <button @click="goBack" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
          Back
        </button>
        <button @click="editApplication" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
          Edit Application
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";


defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  application: {
    type: Object,
    required: true
  }
});

const getStatusClasses = (status) => {
  const baseClasses = 'px-2 py-1 rounded-full text-xs font-semibold';
  const statusClasses = {
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
    reverted: 'bg-yellow-100 text-yellow-800'
  };
  return `${baseClasses} ${statusClasses[status] || 'bg-gray-100 text-gray-800'}`;
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const goBack = () => {
  Inertia.get(route('applications.index'));
};

const editApplication = () => {
  Inertia.get(`/applications/${props.application.id}/edit`);
};
</script>

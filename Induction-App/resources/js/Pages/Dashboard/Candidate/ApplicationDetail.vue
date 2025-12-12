<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Breadcrumbs -->
      <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
          <li v-for="(breadcrumb, index) in breadcrumbs" :key="index" class="flex items-center">
            <Link 
              v-if="breadcrumb.url" 
              :href="breadcrumb.url" 
              class="hover:text-teal-600 transition-colors"
            >
              {{ breadcrumb.name }}
            </Link>
            <span v-else class="text-gray-800 font-medium">{{ breadcrumb.name }}</span>
            <i v-if="index < breadcrumbs.length - 1" class="fas fa-chevron-right mx-2 text-gray-400"></i>
          </li>
        </ol>
      </nav>

      <!-- Application Header -->
      <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="flex justify-between items-start mb-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Application Details</h1>
            <p class="text-gray-600">Application ID: #{{ application.id }}</p>
          </div>
          <div class="flex space-x-4">
            <button
              @click="downloadApplication"
              class="flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors"
            >
              <i class="fas fa-download mr-2"></i>
              Download
            </button>
            <button
              v-if="canMakePayment"
              @click="makePayment"
              class="flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors"
            >
              <i class="fas fa-credit-card mr-2"></i>
              Make Payment
            </button>
          </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
          <span
            class="status-badge"
            :class="{
              'bg-teal-200 text-teal-800': application.status === 'Roll Number Slip Generated' || application.status === 'approved',
              'bg-yellow-200 text-yellow-800': application.status === 'pending',
              'bg-red-200 text-red-800': application.status === 'rejected',
              'bg-gray-200 text-gray-800': !application.status
            }"
          >
            {{ formatStatus(application.status) }}
          </span>
        </div>
      </div>

      <!-- Application Information -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Job Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Job Information</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
              <p class="text-gray-900">{{ application.post?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <p class="text-gray-900">{{ application.post?.post_category?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Required Qualification</label>
              <p class="text-gray-900">{{ application.post?.qualification_type?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Preferred City</label>
              <p class="text-gray-900">{{ application.city?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alternative City</label>
              <p class="text-gray-900">{{ application.alternative_city?.name || 'N/A' }}</p>
            </div>
          </div>
        </div>

        <!-- Application Details -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Application Details</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date Applied</label>
              <p class="text-gray-900">{{ formatDate(application.applied_at) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
              <p class="text-gray-900">{{ formatDate(application.updated_at) }}</p>
            </div>
            <div v-if="application.rejection_reason">
              <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
              <p class="text-red-600">{{ application.rejection_reason }}</p>
            </div>
          </div>
        </div>

        <!-- Candidate Profile -->
        <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-2">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Your Profile</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <p class="text-gray-900">{{ application.user?.candidate_profile?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Father's Name</label>
              <p class="text-gray-900">{{ application.user?.candidate_profile?.father_name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
              <p class="text-gray-900">{{ application.user?.candidate_profile?.cnic || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
              <p class="text-gray-900">{{ formatDate(application.user?.candidate_profile?.date_of_birth) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
              <p class="text-gray-900">{{ application.user?.candidate_profile?.gender || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
              <p class="text-gray-900">{{ application.user?.candidate_profile?.religion || 'N/A' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Back Button -->
      <div class="mt-8 text-center">
        <Link
          :href="route('candidate.dashboard')"
          class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          Back to Dashboard
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Candidatelayout from "@/Layouts/Candidatelayout.vue";

defineOptions({
  layout: Candidatelayout,
});

const props = defineProps({
  application: {
    type: Object,
    required: true,
  },
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
  auth: {
    type: Object,
    default: () => ({}),
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
});

// Computed properties
const canMakePayment = computed(() => {
  return props.application.status === 'approved' || 
         props.application.status === 'Roll Number Slip Generated';
});

// Methods
const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const formatStatus = (status) => {
  if (!status) return 'N/A';
  return status.charAt(0).toUpperCase() + status.slice(1);
};

const downloadApplication = () => {
  window.open(route('candidate.applications.download', props.application.id), '_blank');
};

const makePayment = () => {
  if (!canMakePayment.value) {
    alert('Payment not available for this application status');
    return;
  }
  
  // Navigate to payment page or open payment modal
  router.post(route('candidate.applications.payment', props.application.id));
};
</script>

<style scoped>
.status-badge {
  padding: 8px 16px;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
</style>
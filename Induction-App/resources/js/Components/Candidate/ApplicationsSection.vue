<template>
  <div id="my-applications" class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 px-8 py-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-2xl font-bold text-white">My Job Applications</h3>
            <p class="text-teal-100 text-sm">Track and manage all your submissions</p>
          </div>
        </div>
        <div class="text-right">
          <div class="text-3xl font-bold text-white">{{ applications.length }}</div>
          <div class="text-teal-100 text-sm">Total Applications</div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!applications.length" class="p-16 text-center">
      <div class="w-24 h-24 bg-gradient-to-br from-teal-100 to-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </div>
      <h4 class="text-xl font-bold text-gray-900 mb-2">No Applications Yet</h4>
      <p class="text-gray-600 mb-6 max-w-md mx-auto">Start your journey by applying to available positions. Browse open posts and submit your application today!</p>
      <a href="#active-services" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-blue-600 text-white rounded-xl font-medium hover:from-teal-700 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        Browse Open Positions
      </a>
    </div>

    <!-- Applications Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">ID</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Job Title</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Test Center</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Applied Date</th>
            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr
            v-for="application in paginatedApplications"
            :key="application.id"
            class="hover:bg-gradient-to-r hover:from-teal-50 hover:to-blue-50 transition-all duration-200"
          >
            <td class="px-6 py-5">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
                  {{ application.id }}
                </div>
              </div>
            </td>
            <td class="px-6 py-5">
              <div class="font-semibold text-gray-900">{{ application.post?.name || 'N/A' }}</div>
              <div class="text-sm text-gray-500">Post ID: {{ application.post_id }}</div>
            </td>
            <td class="px-6 py-5">
              <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-medium">{{ application.preferred_city_id || 'Not specified' }}</span>
              </div>
            </td>
            <td class="px-6 py-5">
              <div class="flex items-center gap-2 text-gray-700">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-medium">{{ formatDate(application.applied_at) }}</span>
              </div>
            </td>
            <td class="px-6 py-5">
              <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm"
                :class="getStatusClass(application.status)"
              >
                <span class="w-2 h-2 rounded-full" :class="getStatusDotClass(application.status)"></span>
                {{ formatStatus(application.status) }}
              </span>
            </td>
            <td class="px-6 py-5">
              <div class="flex items-center justify-center gap-2">
                <!-- View Button -->
                <button
                  @click="viewApplication(application.id)"
                  class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-all shadow-sm hover:shadow-md group"
                  title="View Details"
                >
                  <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </button>

                <!-- Payment Menu -->
                <div class="relative">
                  <button
                    @click="togglePaymentMenu(application.id)"
                    :disabled="!canMakePayment(application)"
                    class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-lg font-medium transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                    title="Payment Options"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Pay
                  </button>

                  <!-- Dropdown Menu -->
                  <div
                    v-show="openPaymentMenu === application.id"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-200 z-10 overflow-hidden"
                  >
                    <button
                      @click="makePayment(application.id)"
                      class="w-full px-4 py-3 text-left hover:bg-emerald-50 flex items-center gap-3 transition-colors"
                    >
                      <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span class="font-medium text-gray-700">Pay Online</span>
                    </button>
                    <inertia-link
                      :href="route('candidate.applications.challan', application.id)"
                      class="w-full px-4 py-3 text-left hover:bg-orange-50 flex items-center gap-3 transition-colors border-t border-gray-100"
                      @click="closePaymentMenu"
                    >
                      <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                      <span class="font-medium text-gray-700">View Challan</span>
                    </inertia-link>
                  </div>
                </div>

                <!-- Download Button -->
                <button
                  @click="downloadApplication(application.id)"
                  class="p-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-all shadow-sm hover:shadow-md group"
                  title="Download Application"
                >
                  <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-t-2 border-gray-200">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <!-- Items per page -->
          <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Items per page:</label>
            <select
              v-model="itemsPerPage"
              @change="updatePagination"
              class="px-4 py-2 border-2 border-gray-300 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all font-medium text-gray-700"
            >
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
            </select>
          </div>

          <!-- Page info -->
          <div class="text-sm font-medium text-gray-700">
            Showing <span class="font-bold text-teal-600">{{ startItem }}</span> to
            <span class="font-bold text-teal-600">{{ endItem }}</span> of
            <span class="font-bold text-teal-600">{{ totalItems }}</span> applications
          </div>

          <!-- Page controls -->
          <div class="flex items-center gap-2">
            <button
              @click="goToPage(1)"
              :disabled="currentPage === 1"
              class="p-2 border-2 border-gray-300 rounded-lg bg-white hover:bg-teal-50 hover:border-teal-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
              </svg>
            </button>
            <button
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="p-2 border-2 border-gray-300 rounded-lg bg-white hover:bg-teal-50 hover:border-teal-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>
            <div class="px-4 py-2 bg-gradient-to-r from-teal-600 to-blue-600 text-white rounded-lg font-bold shadow-md">
              {{ currentPage }} / {{ totalPages }}
            </div>
            <button
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="p-2 border-2 border-gray-300 rounded-lg bg-white hover:bg-teal-50 hover:border-teal-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
            <button
              @click="goToPage(totalPages)"
              :disabled="currentPage === totalPages"
              class="p-2 border-2 border-gray-300 rounded-lg bg-white hover:bg-teal-50 hover:border-teal-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  myJobsApplication: {
    type: Object,
    default: () => ({}),
  },
});

const applications = ref(props.myJobsApplication.applied_posts || []);
const itemsPerPage = ref(10);
const currentPage = ref(1);
const openPaymentMenu = ref(null);

watch(
  () => props.myJobsApplication,
  (val) => {
    applications.value = val?.applied_posts || [];
  },
  { deep: true }
);

const totalItems = computed(() => applications.value.length);
const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));
const startItem = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1);
const endItem = computed(() => Math.min(currentPage.value * itemsPerPage.value, totalItems.value));

const paginatedApplications = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return applications.value.slice(start, end);
});

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const formatStatus = (status) => {
  if (!status) return 'Pending';
  return status.charAt(0).toUpperCase() + status.slice(1);
};

const getStatusClass = (status) => {
  const s = String(status || '').toLowerCase();
  if (s.includes('approved') || s.includes('roll number')) {
    return 'bg-emerald-100 text-emerald-800 border-2 border-emerald-300';
  }
  if (s === 'pending' || !status) {
    return 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300';
  }
  if (s === 'rejected') {
    return 'bg-red-100 text-red-800 border-2 border-red-300';
  }
  return 'bg-gray-100 text-gray-800 border-2 border-gray-300';
};

const getStatusDotClass = (status) => {
  const s = String(status || '').toLowerCase();
  if (s.includes('approved') || s.includes('roll number')) return 'bg-emerald-500 animate-pulse';
  if (s === 'pending' || !status) return 'bg-yellow-500 animate-pulse';
  if (s === 'rejected') return 'bg-red-500';
  return 'bg-gray-500';
};

const canMakePayment = (application) => {
  if (!application?.status) return false;
  return String(application.status).toLowerCase() !== 'rejected';
};

const togglePaymentMenu = (id) => {
  openPaymentMenu.value = openPaymentMenu.value === id ? null : id;
};

const closePaymentMenu = () => {
  openPaymentMenu.value = null;
};

const updatePagination = () => {
  currentPage.value = 1;
};

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const viewApplication = (id) => {
  router.visit(`/applications/${id}`, { method: 'get' });
};

const makePayment = (id) => {
  closePaymentMenu();
  router.visit(`/candidate/applications/${id}/payment`, { method: 'get' });
};

const downloadApplication = (id) => {
  window.open(`/candidate/applications/${id}/download`, '_blank');
};
</script>

<template>
  <div class="p-6 relative min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Center Allotments</h1>

    <!-- Filters -->
    <div class="mb-4 grid grid-cols-5 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Filter by Post</label>
        <select 
          v-model="filters.post_id" 
          @change="fetchApplications" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
          <option value="">All Posts</option>
          <option v-for="post in posts" :key="post.id" :value="post.id">
            {{ post.name }}
          </option>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-gray-700">Filter by City</label>
        <select 
          v-model="filters.city_id" 
          @change="fetchApplications" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
          <option value="">All Cities</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">
            {{ city.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Filter by Status</label>
        <select 
          v-model="filters.status" 
          @change="fetchApplications" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
          <option value="">All Status</option>
          <option value="allocated">Allocated</option>
          <option value="not_allocated">Not Allocated</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Filter by Center</label>
        <select 
          v-model="filters.center_id" 
          @change="fetchApplications" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        >
          <option value="">All Centers</option>
          <option v-for="center in centers" :key="center.id" :value="center.id">
            {{ center.name }}
          </option>
        </select>
      </div>

      <div class="flex space-x-2">
        <button 
          @click="allocateCenters" 
          :disabled="isAllocating"
          class="mt-6 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
        >
          {{ isAllocating ? 'Allocating...' : 'Allocate Centers' }}
        </button>
        <button 
          @click="loadReports" 
          class="mt-6 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
          View Reports
        </button>
      </div>
    </div>

    <!-- Applications Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 bg-white shadow-sm rounded-lg overflow-hidden">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preferred City</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roll Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Center</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="application in applications" :key="application.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ application.candidate_name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ application.post_name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ application.preferred_city }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ application.roll_number || 'Not Allotted' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ application.center_name || 'Not Allotted' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <span 
                :class="{
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                  'bg-green-100 text-green-800': application.status === 'allotted',
                  'bg-yellow-100 text-yellow-800': application.status === 'pending',
                  'bg-red-100 text-red-800': application.status === 'rejected'
                }"
              >
                {{ application.status || 'Pending' }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Allocation Preview Modal -->
    <div v-if="showAllocationPreview" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="showAllocationPreview = false">
      <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-[95%] shadow-lg rounded-md bg-white" @click.stop>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-2xl font-bold text-gray-900">Allocation Preview</h3>
          <button @click="showAllocationPreview = false" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="allocationPreview" class="space-y-6">
          <div class="grid grid-cols-2 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg">
              <h4 class="font-bold text-lg mb-3">Summary</h4>
              <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded shadow">
                  <p class="text-sm text-gray-600">Total to Allocate</p>
                  <p class="text-2xl font-bold text-blue-600">{{ allocationPreview.total }}</p>
                </div>
                <div class="bg-white p-3 rounded shadow">
                  <p class="text-sm text-gray-600">Already Allocated</p>
                  <p class="text-2xl font-bold text-green-600">{{ allocationPreview.already_allocated }}</p>
                </div>
                <div class="bg-white p-3 rounded shadow">
                  <p class="text-sm text-gray-600">Pending</p>
                  <p class="text-2xl font-bold text-yellow-600">{{ allocationPreview.pending }}</p>
                </div>
              </div>
            </div>

            <!-- Preference Analysis -->
            <div v-if="allocationPreview.preference_stats" class="bg-indigo-50 p-4 rounded-lg">
              <h4 class="font-bold text-lg mb-3">Preference Analysis (Estimated)</h4>
              <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded shadow border-l-4 border-green-500">
                  <p class="text-sm text-gray-600">Likely City 1</p>
                  <p class="text-2xl font-bold text-green-600">{{ allocationPreview.preference_stats.city_1 }}</p>
                </div>
                <div class="bg-white p-3 rounded shadow border-l-4 border-blue-500">
                  <p class="text-sm text-gray-600">Likely City 2</p>
                  <p class="text-2xl font-bold text-blue-600">{{ allocationPreview.preference_stats.city_2 }}</p>
                </div>
                <div class="bg-white p-3 rounded shadow border-l-4 border-red-500">
                  <p class="text-sm text-gray-600">Risk Unmatched</p>
                  <p class="text-2xl font-bold text-red-600">{{ allocationPreview.preference_stats.unmatched }}</p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <h4 class="font-bold text-lg mb-3">Allocation Breakdown by City</h4>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Centers Available</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applicants</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="city in allocationPreview.by_city" :key="city.city_id">
                    <td class="px-4 py-3 text-sm font-medium">{{ city.city_name }}</td>
                    <td class="px-4 py-3 text-sm">{{ city.centers_count }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-blue-600">{{ city.applicants_count }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Post-wise Summary -->
          <div v-if="allocationPreview.by_post && allocationPreview.by_post.length > 0">
            <h4 class="font-bold text-lg mb-3">Post-wise Summary</h4>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pending Applicants</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="post in allocationPreview.by_post" :key="post.post_name">
                    <td class="px-4 py-3 text-sm font-medium">{{ post.post_name }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-blue-600">{{ post.applicants_count }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Detailed Center-Post Breakdown -->
          <div v-if="allocationPreview.center_post_breakdown && allocationPreview.center_post_breakdown.length > 0">
            <h4 class="font-bold text-lg mb-3">📋 Detailed Allocation Plan (Center × Post)</h4>
            <div class="overflow-x-auto max-h-96">
              <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                <thead class="bg-gray-50 sticky top-0">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Center</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Will Allocate</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="(item, index) in allocationPreview.center_post_breakdown" :key="index">
                    <td class="px-4 py-3 text-sm">{{ item.city_name }}</td>
                    <td class="px-4 py-3 text-sm">{{ item.center_name }}</td>
                    <td class="px-4 py-3 text-sm font-medium">{{ item.post_name }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-green-600">{{ item.applicants_count }}</td>
                    <td class="px-4 py-3 text-sm">{{ item.current_allocated }}</td>
                    <td class="px-4 py-3 text-sm">{{ item.max_applicants || 'N/A' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-yellow-700">
                  This action will allocate centers and generate roll numbers for <strong>{{ allocationPreview.pending }}</strong> applicants. This action cannot be undone.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showAllocationPreview = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
            Cancel
          </button>
          <button @click="confirmAllocation" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Confirm & Allocate
          </button>
        </div>
      </div>
    </div>

    <!-- Reports Modal -->
    <div v-if="showReportsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="showReportsModal = false">
      <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-[95%] shadow-lg rounded-md bg-white max-h-screen overflow-y-auto" @click.stop>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-2xl font-bold text-gray-900">Allocation Reports</h3>
          <button @click="showReportsModal = false" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="reportsLoading" class="text-center py-8">
          <p>Loading reports...</p>
        </div>

        <div v-else-if="reports" class="space-y-6">
          <!-- Overall Statistics -->
          <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-bold text-lg mb-3">Overall Statistics</h4>
            <div class="grid grid-cols-4 gap-4">
              <div class="bg-white p-3 rounded shadow">
                <p class="text-sm text-gray-600">Total Approved</p>
                <p class="text-2xl font-bold text-blue-600">{{ reports.overallStats.total_approved_applications }}</p>
              </div>
              <div class="bg-white p-3 rounded shadow">
                <p class="text-sm text-gray-600">Total Allocated</p>
                <p class="text-2xl font-bold text-green-600">{{ reports.overallStats.total_allocated }}</p>
              </div>
              <div class="bg-white p-3 rounded shadow">
                <p class="text-sm text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ reports.overallStats.pending_allocation }}</p>
              </div>
              <div class="bg-white p-3 rounded shadow">
                <p class="text-sm text-gray-600">Allocation %</p>
                <p class="text-2xl font-bold text-purple-600">{{ reports.overallStats.allocation_percent }}%</p>
              </div>
            </div>
          </div>

          <!-- Center-wise Statistics -->
          <div>
            <h4 class="font-bold text-lg mb-3">Center-wise Allocation</h4>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Center</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Allocated</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilization</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="center in reports.centerStats" :key="center.center_id">
                    <td class="px-4 py-3 text-sm">{{ center.center_name }}</td>
                    <td class="px-4 py-3 text-sm">{{ center.city_name }}</td>
                    <td class="px-4 py-3 text-sm font-semibold">
                      <button 
                        @click="filterByCenter(center.center_id)" 
                        class="text-blue-600 hover:underline hover:text-blue-800 focus:outline-none"
                        title="Click to view allocated applicants"
                      >
                        {{ center.total_allocated }}
                      </button>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ center.capacity }}</td>
                    <td class="px-4 py-3 text-sm">
                      <span :class="{
                        'px-2 py-1 rounded text-xs font-semibold': true,
                        'bg-green-100 text-green-800': center.utilization_percent < 80,
                        'bg-yellow-100 text-yellow-800': center.utilization_percent >= 80 && center.utilization_percent < 95,
                        'bg-red-100 text-red-800': center.utilization_percent >= 95
                      }">
                        {{ center.utilization_percent }}%
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Post-wise Statistics -->
          <div>
            <h4 class="font-bold text-lg mb-3">Post-wise Allocation</h4>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Allocated</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pending</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="post in reports.postStats" :key="post.post_name">
                    <td class="px-4 py-3 text-sm font-medium">{{ post.post_name }}</td>
                    <td class="px-4 py-3 text-sm">{{ post.total_approved_applications }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-600">{{ post.total_allocated }}</td>
                    <td class="px-4 py-3 text-sm text-yellow-600">{{ post.pending_allocation }}</td>
                    <td class="px-4 py-3 text-sm">
                      <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                          <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${post.allocation_percent}%`"></div>
                        </div>
                        <span class="text-xs font-semibold">{{ post.allocation_percent }}%</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button @click="showReportsModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Custom Loading Overlay -->
    <div v-if="isLoading" class="fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
      <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col items-center transform transition-all scale-100 border border-gray-100 max-w-sm w-full mx-4">
        <div class="relative flex items-center justify-center h-32 w-32 mb-4">
          <!-- Outer Ring -->
          <div class="absolute inset-0 rounded-full border-[6px] border-green-100 border-t-green-600 animate-spin" style="animation-duration: 2s;"></div>
          <!-- Middle Ring -->
          <div class="absolute inset-3 rounded-full border-[5px] border-green-50 border-r-green-500 animate-spin" style="animation-duration: 3s; animation-direction: reverse;"></div>
          <!-- Logo Container -->
          <div class="relative z-10 bg-white rounded-full p-2 shadow-sm">
            <img :src="'/images/fgei.png'" alt="FGEI Logo" class="h-16 w-16 object-contain animate-pulse" />
          </div>
        </div>
        <div class="text-center space-y-2">
          <h3 class="text-xl font-bold text-gray-800 tracking-tight">FGEI Induction System</h3>
          <div class="flex items-center justify-center space-x-1">
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
          </div>
          <p class="text-sm text-gray-500 font-medium">Processing your request...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import axios from 'axios';

const filters = ref({
  post_id: '',
  city_id: '',
  status: '',
  center_id: '',
});

const applications = ref([]);
const posts = ref([]);
const cities = ref([]);
const centers = ref([]);
const showReportsModal = ref(false);
const reports = ref(null);
const reportsLoading = ref(false);
const showAllocationPreview = ref(false);
const allocationPreview = ref(null);
const isAllocating = ref(false);
const isLoading = ref(false);

const fetchApplications = () => {
  router.get('/center-allotments', filters.value, {
    preserveState: true,
    preserveScroll: true,
    onStart: () => { isLoading.value = true; },
    onFinish: () => { isLoading.value = false; },
    onSuccess: (page) => {
      applications.value = page.props.applications;
      posts.value = page.props.posts;
      cities.value = page.props.cities;
      centers.value = page.props.centers || [];
    },
    onError: () => {
      toast.error('Failed to fetch applications.');
    },
  });
};

const loadReports = async () => {
  showReportsModal.value = true;
  reportsLoading.value = true;
  
  try {
    const response = await axios.get('/center-allotments/reports');
    reports.value = response.data.props;
  } catch (error) {
    toast.error('Failed to load reports');
    console.error(error);
  } finally {
    reportsLoading.value = false;
  }
};

const loadAllocationPreview = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/center-allotments/preview');
    allocationPreview.value = response.data;
    showAllocationPreview.value = true;
  } catch (error) {
    toast.error('Failed to load allocation preview');
    console.error(error);
  } finally {
    isLoading.value = false;
  }
};

const confirmAllocation = async () => {
  isAllocating.value = true;
  showAllocationPreview.value = false;
  isLoading.value = true;

  try {
    await router.post('/center-allotments/allocate', {}, {
      onSuccess: () => {
        toast.success('Centers allocated successfully!');
        fetchApplications();
      },
      onError: (errors) => {
        toast.error('Failed to allocate centers.');
        console.error(errors);
        isLoading.value = false;
      },
      onFinish: () => {
        // isLoading handled in fetchApplications if success, or here if error
      }
    });
  } catch (error) {
    toast.error('An unexpected error occurred.');
    console.error(error);
    isLoading.value = false;
    isAllocating.value = false;
  }
};

const allocateCenters = () => {
  loadAllocationPreview();
};

const filterByCenter = (centerId) => {
  filters.value.center_id = centerId;
  filters.value.status = 'allocated';
  showReportsModal.value = false;
  fetchApplications();
};

onMounted(() => {
  fetchApplications();
});
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
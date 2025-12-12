<template>
  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Quota Setting</h2>
      </div>

      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
              <!-- Induction Phase and Post Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700">Induction Phase *</label>
                <select
                  v-model="form.induction_phase_id"
                  @change="fetchPostsByInductionPhase"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  :disabled="loadingPosts"
                  required
                >
                  <option value="">Select Induction Phase</option>
                  <option v-for="phase in inductionPhases" :key="phase.id" :value="phase.id">
                    {{ phase.title }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Post Name*</label>
                <select
                  v-model="form.post_id"
                  @change="fetchPostDetails"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  :disabled="!form.induction_phase_id || loadingPosts"
                  required
                >
                  <option value="">Select Post</option>
                  <option v-for="post in posts" :key="post.id" :value="post.id">
                    {{ post.name }} ({{ post.post_abbreviation }})
                  </option>
                </select>
                <div v-if="loadingPosts" class="mt-2 text-sm text-gray-500">
                  Loading posts...
                </div>
              </div>

              <!-- Post Details -->
              <div v-if="showPostDetails">
                <label class="block text-sm font-medium text-gray-700">Name Of Post</label>
                <input
                  v-model="postDetails.name"
                  type="text"
                  readonly
                  class="block w-full mt-1 text-gray-600 bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>

              <div v-if="showPostDetails">
                <label class="block text-sm font-medium text-gray-700">No Of Posts</label>
                <input
                  v-model="postDetails.number_post"
                  type="number"
                  readonly
                  class="block w-full mt-1 text-gray-600 bg-gray-100 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
              </div>
            </div>

            <!-- Open Merit Field (Separate from Province Merit) -->
            <div v-if="showPostDetails" class="mt-8">
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Open Merit (Not Province Specific)</label>
                <input
                  v-model.number="form.open_merit"
                  type="number"
                  min="0"
                  :max="postDetails.number_post"
                  class="block w-full sm:w-64 p-3 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"
                  placeholder="Enter open merit posts"
                />
                <p class="mt-1 text-sm text-gray-500">This is separate from province-specific merit allocations below</p>
              </div>
            </div>

            <!-- Quota Allocation Table -->
            <div v-if="showPostDetails" class="mt-8">
              <h3 class="mb-4 text-lg font-medium text-gray-900">Province-wise Quota Allocation</h3>

              <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                  <!-- Header Row -->
                  <thead>
                    <tr class="bg-gray-100">
                      <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700 min-w-[150px]">
                        Province
                      </th>
                      <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700 min-w-[120px]">
                        Merit
                      </th>
                      <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700 min-w-[120px]">
                        Women
                      </th>
                      <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700 min-w-[120px]">
                        Minority
                      </th>
                      <th class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-700 min-w-[140px]">
                        Special Persons
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="Province in props.Province" :key="Province.id" class="hover:bg-gray-50">
                      <!-- Province Name -->
                      <td class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50">
                        {{ Province.name.toUpperCase() }}
                      </td>

                      <!-- Merit Input (Province Specific) -->
                      <td class="border border-gray-300 px-2 py-2">
                        <input
                          v-model.number="form.quota_allocation.merit[Province.id]"
                          type="number"
                          min="0"
                          :max="postDetails.number_post"
                          class="w-full p-2 text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"
                          placeholder="0"
                        />
                      </td>

                      <!-- Women Input -->
                      <td class="border border-gray-300 px-2 py-2">
                        <input
                          v-model.number="form.quota_allocation.women[Province.id]"
                          type="number"
                          min="0"
                          :max="postDetails.number_post"
                          class="w-full p-2 text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"
                          placeholder="0"
                        />
                      </td>

                      <!-- Minority Input -->
                      <td class="border border-gray-300 px-2 py-2">
                        <input
                          v-model.number="form.quota_allocation.minority[Province.id]"
                          type="number"
                          min="0"
                          :max="postDetails.number_post"
                          class="w-full p-2 text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"
                          placeholder="0"
                        />
                      </td>

                      <!-- Special Persons Input -->
                      <td class="border border-gray-300 px-2 py-2">
                        <input
                          v-model.number="form.quota_allocation.special_persons[Province.id]"
                          type="number"
                          min="0"
                          :max="postDetails.number_post"
                          class="w-full p-2 text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-1"
                          placeholder="0"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Enhanced Total Summary -->
              <div class="mt-6 from-blue-50 to-indigo-50 rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="custombg px-6 py-4">
                  <h4 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Quota Summary
                  </h4>
                </div>

                <!-- Stats Grid -->
                <div class="p-6">
                  <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Open Merit Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-oranges hover:shadow-lg transition-shadow duration-200">
                      <div class="flex items-center justify-between">
                        <div>
                          <p class="text-sm font-medium text-gray-600 mb-1">Open Merit</p>
                          <p class="custom-color text-2xl font-bold">{{ form.open_merit || 0 }}</p>
                        </div>

                      </div>
                    </div>

                    <!-- Province Merit Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-oranges hover:shadow-lg transition-shadow duration-200">
                      <div class="flex items-center justify-between">
                        <div>
                          <p class="text-sm font-medium text-gray-600 mb-1">Province Merit</p>
                          <p class="text-2xl font-bold custom-color">{{ getTotalForCategory('merit') }}</p>
                        </div>

                      </div>
                    </div>

                    <!-- Women Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-oranges hover:shadow-lg transition-shadow duration-200">
                      <div class="flex items-center justify-between">
                        <div>
                          <p class="text-sm font-medium text-gray-600 mb-1">Women</p>
                          <p class="text-2xl font-bold custom-color">{{ getTotalForCategory('women') }}</p>
                        </div>

                      </div>
                    </div>

                    <!-- Minority Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-oranges hover:shadow-lg transition-shadow duration-200">
                      <div class="flex items-center justify-between">
                        <div>
                          <p class="text-sm font-medium text-gray-600 mb-1">Minority</p>
                          <p class="text-2xl font-bold custom-color">{{ getTotalForCategory('minority') }}</p>
                        </div>

                      </div>
                    </div>

                    <!-- Special Persons Card -->
                    <div class="bg-white rounded-lg p-4 shadow-md border-l-4 border-oranges hover:shadow-lg transition-shadow duration-200">
                      <div class="flex items-center justify-between">
                        <div>
                          <p class="text-sm font-medium text-gray-600 mb-1">Special Persons</p>
                          <p class="text-2xl font-bold custom-color">{{ getTotalForCategory('special_persons') }}</p>
                        </div>

                      </div>
                    </div>
                  </div>

                  <!-- Grand Total Section -->
                  <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg p-6 text-center text-white shadow-lg">
                      <div class="flex items-center justify-center mb-2">
                        <svg class="w-8 h-8 mr-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h5 class="text-lg font-semibold">Grand Total</h5>
                      </div>
                      <div class="flex items-center justify-center space-x-4">
                        <div class="text-3xl font-bold text-yellow-400">{{ getGrandTotal() }}</div>
                        <div class="text-xl text-gray-300">/</div>
                        <div class="text-2xl font-semibold text-white">{{ postDetails.number_post }}</div>
                      </div>
                      <div class="mt-2">
                        <div class="w-full bg-gray-700 rounded-full h-3">
                          <div
                            class="h-3 rounded-full transition-all duration-500 ease-out"
                            :class="getGrandTotal() > postDetails.number_post ? 'bg-red-500' : 'bg-gradient-to-r from-green-400 to-blue-500'"
                            :style="`width: ${Math.min((getGrandTotal() / postDetails.number_post) * 100, 100)}%`"
                          ></div>
                        </div>
                        <p class="text-sm mt-2 text-white font-medium">
                          {{ getGrandTotal() > postDetails.number_post ? 'Quota Exceeded!' : 'Available Posts' }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          <div>
                    <div v-if="getGrandTotal() > postDetails.number_post">
                    Quota Exceeded!
                    </div>
                    <div v-else>

                    <div class="flex justify-end mt-6">
                        <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-[#0e5723] rounded-md shadow-sm hover:bg-[#1B2850] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0e5723]"
                        :disabled="form.processing"
                        >
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Update</span>
                        </button>
                    </div>
                    </div>
                </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  inductionPhases: Array,
  QuotaAllocation: Array,
  Province: Array,
  errors: Object,
});

const posts = ref([]);
const loadingPosts = ref(false);
const showPostDetails = ref(false);
const postDetails = ref({
  name: '',
  number_post: 0,
  post_abbreviation: ''
});

const form = useForm({
  induction_phase_id: null,
  post_id: null,
  open_merit: 0, // This is separate from province merit
  quota_allocation: {
    merit: {}, // This is province-specific merit
    women: {},
    minority: {},
    special_persons: {},
  },
});

// Initialize form with all Provinces
onMounted(() => {
  if (props.Province && props.Province.length) {
    props.Province.forEach(province => {
      form.quota_allocation.merit[province.id] = 0;
      form.quota_allocation.women[province.id] = 0;
      form.quota_allocation.minority[province.id] = 0;
      form.quota_allocation.special_persons[province.id] = 0;
    });
  }
});

// Calculate totals for each category (province-specific only)
const getTotalForCategory = (category) => {
  if (!props.Province) return 0;
  return props.Province.reduce((total, province) => {
    return total + (form.quota_allocation[category][province.id] || 0);
  }, 0);
};

// Calculate grand total including open merit
const getGrandTotal = () => {
  return (form.open_merit || 0) +
         getTotalForCategory('merit') +
         getTotalForCategory('women') +
         getTotalForCategory('minority') +
         getTotalForCategory('special_persons');
};

const fetchPostsByInductionPhase = async () => {
  if (!form.induction_phase_id) {
    posts.value = [];
    form.post_id = null;
    showPostDetails.value = false;
    return;
  }

  try {
    loadingPosts.value = true;
    posts.value = [];
    form.post_id = null;
    showPostDetails.value = false;

    const response = await axios.get(route("induction-phase-Post"), {
      params: {
        induction_phase_id: form.induction_phase_id,
      },
    });

    posts.value = response.data;
  } catch (error) {
    console.error("Error fetching posts:", error);
  } finally {
    loadingPosts.value = false;
  }
};

const fetchPostDetails = async () => {
  if (!form.post_id) {
    showPostDetails.value = false;
    return;
  }

  try {
    const response = await axios.get(route("Get-Post"), {
      params: {
        post_id: form.post_id,
      },
    });

    if (response.data && response.data.length > 0) {
      postDetails.value = response.data[0];
      showPostDetails.value = true;

      // Reset quota allocations when post changes
      resetQuotaAllocations();

      // Load existing quota data if available
      loadExistingQuotaData();
    } else {
      showPostDetails.value = false;
    }
  } catch (error) {
    console.error("Error fetching post details:", error);
    showPostDetails.value = false;
  }
};

const loadExistingQuotaData = () => {
  if (!form.induction_phase_id || !form.post_id || !props.QuotaAllocation) {
    return;
  }

  // Filter existing quota data for current induction phase and post
  const existingData = props.QuotaAllocation.filter(quota =>
    quota.induction_phase_id == form.induction_phase_id &&
    quota.post_id == form.post_id
  );

  if (existingData.length > 0) {
    // Load open merit (where province_id is null)
    const openMeritData = existingData.find(quota => quota.province_id === null);
    if (openMeritData) {
      form.open_merit = openMeritData.open_merit || 0;
    }

    // Load province-specific data
    existingData.forEach(quota => {
      if (quota.province_id !== null) {
        const provinceId = quota.province_id;
        form.quota_allocation.merit[provinceId] = quota.merit || 0;
        form.quota_allocation.women[provinceId] = quota.women || 0;
        form.quota_allocation.minority[provinceId] = quota.minority || 0;
        form.quota_allocation.special_persons[provinceId] = quota.special_persons || 0;
      }
    });

    console.log('Loaded existing quota data:', existingData);
  }
};

const resetQuotaAllocations = () => {
  form.open_merit = 0;
  if (props.Province && props.Province.length) {
    props.Province.forEach(province => {
      form.quota_allocation.merit[province.id] = 0;
      form.quota_allocation.women[province.id] = 0;
      form.quota_allocation.minority[province.id] = 0;
      form.quota_allocation.special_persons[province.id] = 0;
    });
  }
};

const submit = () => {
  // Create an array to hold all quota records
  const quotaRecords = [];

  // Add open merit record (separate from province merit)
  quotaRecords.push({
    induction_phase_id: form.induction_phase_id,
    post_id: form.post_id,
    province_id: null, // Open merit is not tied to any province
    open_merit: form.open_merit || 0,
    merit: 0, // Open merit record doesn't have province merit
    women: 0,
    minority: 0,
    special_persons: 0,
  });

  // Add province-specific quota records
  if (props.Province && Array.isArray(props.Province)) {
    props.Province.forEach(province => {
      const merit = form.quota_allocation.merit[province.id] || 0;
      const women = form.quota_allocation.women[province.id] || 0;
      const minority = form.quota_allocation.minority[province.id] || 0;
      const special_persons = form.quota_allocation.special_persons[province.id] || 0;

      quotaRecords.push({
        induction_phase_id: form.induction_phase_id,
        post_id: form.post_id,
        province_id: province.id,
        open_merit: 0, // Province records don't have open merit
        merit: merit,
        women: women,
        minority: minority,
        special_persons: special_persons,
      });
    });
  }

  // Log the data being sent for debugging
  console.log('Quota Records to be sent:', quotaRecords);

  // Submit using Inertia form
  form.transform(() => quotaRecords)
    .post(route('QuotaSetting.store'), {
      onSuccess: () => {
        console.log('Quota settings saved successfully');
        // Reload the page to get updated data
        window.location.reload();
      },
      onError: (errors) => {
        console.error('Validation errors:', errors);
      },
    });
};
</script>

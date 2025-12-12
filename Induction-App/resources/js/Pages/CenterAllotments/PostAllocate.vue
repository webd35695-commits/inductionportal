<template>
  <div class="space-y-4">
    <!-- Selection Controls in One Line -->
    <div class="flex flex-wrap items-end gap-4">
      <!-- Induction Phase Dropdown -->
      <div class="flex-1 min-w-[200px]">
        <label class="block text-sm font-medium text-gray-700 mb-1">Induction Phase</label>
        <select 
          v-model="selectedInductionPhaseId"
          @change="fetchPostsForInductionPhase"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
          <option value="">Select Phase</option>
          <option 
            v-for="phase in inductionPhases" 
            :value="phase.id"
            :key="phase.id"
          >
            {{ phase.title }}
          </option>
        </select>
      </div>

      <!-- Cities Dropdown -->
      <div class="flex-1 min-w-[200px]">
        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
        <select 
          v-model="selectedCityId"
          @change="fetchCentersForInductionPhase"
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
          <option value="">Select City</option>
          <option 
            v-for="city in examCities" 
            :value="city.id"
            :key="city.id"
          >
            {{ city.name }}
          </option>
        </select>
      </div>

      <!-- Posts Dropdown -->
      <div class="flex-1 min-w-[200px]" v-if="posts.length > 0">
        <label class="block text-sm font-medium text-gray-700 mb-1">Posts</label>
        <select 
          v-model="selectedPostIds"
          multiple
          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-[42px] min-h-[42px]"
        >
          <option 
            v-for="post in posts" 
            :value="post.id"
            :key="post.id"
          >
            {{ post.name }}
          </option>
        </select>
      </div>

      <!-- Allocate Button -->
      <div class="flex-0" v-if="selectedPostIds.length > 0">
        <button 
          @click="processSelectedPosts"
          :disabled="!selectedCityId"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:bg-indigo-300 disabled:cursor-not-allowed h-[42px]"
        >
          Allocate
        </button>
      </div>
    </div>

 

    <!-- Selected Items Summary -->
    <div v-if="selectedItemsCount > 0" class="mt-4 p-4 bg-indigo-50 rounded-lg">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Selected Cities -->
        <div v-if="selectedCityId">
          <h4 class="text-sm font-medium text-indigo-800 mb-1">Selected City</h4>
          <p class="text-indigo-700">
            {{ getCityName(selectedCityId) }}
          </p>
        </div>
        
        <!-- Selected Posts -->
        <div v-if="selectedPostIds.length > 0">
          <h4 class="text-sm font-medium text-indigo-800 mb-1">Selected Posts ({{ selectedPostIds.length }})</h4>
          <ul class="flex flex-wrap gap-1">
            <li v-for="id in selectedPostIds" :key="id" class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">
              {{ getPostName(id) }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div v-else-if="selectedInductionPhaseId && posts.length === 0" class="mt-4 text-gray-500">
      No posts found for this induction phase
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  inductionPhases: {
    type: Array,
    default: () => []
  },
  examCities: {
    type: Array,
    default: () => []
  },
  posts: {
    type: Array,
    default: () => []
  },
  selectedInductionPhaseId: {
    type: [String, Number],
    default: ''
  }
});

// Initialize refs
const inductionPhases = ref(props.inductionPhases);
const examCities = ref(props.examCities); // Keep this separate from posts data
const posts = ref(props.posts);
const selectedInductionPhaseId = ref(props.selectedInductionPhaseId);
const selectedCityId = ref('');
const selectedPostIds = ref([]);

// Watch for examCities changes from props
watch(() => props.examCities, (newCities) => {
  if (newCities && newCities.length > 0) {
    examCities.value = newCities;
  }
}, { immediate: true });

function fetchPostsForInductionPhase() {
  if (!selectedInductionPhaseId.value) {
    posts.value = [];
    selectedPostIds.value = [];
    return;
  }
  
  router.get(route('posts.for.induction', { 
    id: selectedInductionPhaseId.value 
  }), {}, {
    preserveState: true,
    replace: true,
    only: ['posts', 'selectedInductionPhaseId'], // Only fetch these properties
    onSuccess: (page) => {
      posts.value = page.props.posts || [];
      selectedPostIds.value = [];
      // Keep existing examCities data
      if (page.props.examCities) {
        examCities.value = page.props.examCities;
      }
    }
  });
}

// Rest of your methods remain the same
</script>
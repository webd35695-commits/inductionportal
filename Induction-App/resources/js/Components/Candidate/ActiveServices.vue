<!-- Full ActiveServices.vue with disable logic for already applied posts -->
<template>
  <!-- Simple, Clean Error Message -->
  <div v-if="error" class="mb-8">
    <div class="bg-white border border-red-200 rounded-lg p-4 shadow-sm">
      <div class="flex items-center space-x-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="text-gray-700 text-sm">{{ error }}</p>
      </div>
    </div>
  </div>

  <!-- Clean Empty State -->
  <div v-else-if="!posts || posts.length === 0" class="mb-8">
    <div class="text-center py-12">
      <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8zM8 14v.01M12 14v.01M16 14v.01"/>
      </svg>
      <h3 class="text-gray-500 text-lg font-medium mb-2">No active services available</h3>
      <p class="text-gray-400 text-sm">Check back later for new opportunities</p>
    </div>
  </div>

  <!-- Posts Grid -->
  <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <div v-for="post in posts" :key="post.id" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col">
      
      <!-- Card Header -->
      <div class="px-6 pt-6 pb-4 bg-gradient-to-br from-white to-gray-50 flex-1">
        <div class="flex items-start justify-between mb-4">
          <div class="bg-indigo-50 p-3 rounded-xl group-hover:bg-indigo-100 transition-colors duration-300">
             <i class="fas fa-briefcase text-indigo-600 text-xl"></i>
          </div>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            BPS-{{ post.bps }}
          </span>
        </div>
        
        <h4 class="font-bold text-xl text-gray-900 mb-2 leading-tight">{{ post.name }}</h4>
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
           <span class="px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-medium uppercase tracking-wide">
             {{ post.post_gender === 'both' ? 'Male / Female' : post.post_gender }}
           </span>
        </div>
      </div>

      <!-- Card Footer -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 mt-auto">
        <div class="flex items-center justify-between mb-4" v-if="post.deadline">
           <div class="flex flex-col">
              <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Open Till</span>
              <span class="text-sm font-semibold text-gray-900">{{ post.deadline }}</span>
           </div>
        </div>

        <button
          @click="openApplyPopup(post)"
          :disabled="hasApplied(post)"
          class="w-full py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          :class="hasApplied(post) 
             ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
             : 'bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white shadow-indigo-200'"
        >
          {{ hasApplied(post) ? 'Applied' : 'Apply Now' }}
        </button>
        
        <div v-if="hasApplied(post)" class="mt-3 text-center">
             <a href="#my-applications" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View Application</a>
        </div>
      </div>
    </div>

  </div>

  <!-- Apply Now Popup -->
  <ApplyNowPopup
    :testCities="testCities"
    v-if="selectedPost && !hasApplied(selectedPost)"
    :post="selectedPost"
    @close="closeApplyPopup"
  />
</template>

<script setup>
import { ref, computed } from 'vue';
import ApplyNowPopup from './ApplyNowPopup.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  posts: {
    type: Array,
    default: () => [],
  },
  testCities: {
    type: Array,
    default: () => [],
  },
  error: String,
  myJobsApplication: {
    type: Object,
    default: () => ({}),
  },
});

const testCities = props.testCities || [];
const selectedPost = ref(null);

// Computed: Get IDs of already applied posts
const appliedPostIds = computed(() => {
  return props.myJobsApplication.applied_posts?.map(app => app.post?.id) || [];
});

// Check if user has already applied for this post
const hasApplied = (post) => {
  return appliedPostIds.value.includes(post.id);
};

const openApplyPopup = (post) => {
  if (hasApplied(post)) {
    // Optionally redirect to applications section or show message
    alert('You have already applied for this post. Check My Applications.');
    return;
  }
  selectedPost.value = post;
};

const closeApplyPopup = () => {
  selectedPost.value = null;
};

</script>

<style scoped>
.card {
  transition: all 0.4s ease;
  background: linear-gradient(135deg, #ffffff, #f9fafb);
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
}

button:disabled {
  cursor: not-allowed;
}
</style>

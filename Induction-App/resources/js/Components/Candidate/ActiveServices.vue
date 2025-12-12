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
    <div v-for="post in posts" :key="post.id" class="card p-6 rounded-xl shadow-lg text-center">
      <div class="flex justify-center mb-4">
        <i class="fas fa-briefcase text-teal-600 text-3xl"></i>
      </div>
      <h4 class="font-bold text-lg text-gray-800">{{ post.name }}  (BPS-{{ post.bps }}) {{ post.post_gender }}</h4>
      <p class="text-sm text-gray-600 mt-2">Open till {{ post.deadline }}</p>
      <button
        @click="openApplyPopup(post)"
        :disabled="hasApplied(post)"
        :class="{
          'text-teal-700 hover:text-amber-400 font-semibold transition-colors': !hasApplied(post),
          'text-gray-400 cursor-not-allowed opacity-50': hasApplied(post)
        }"
        class="mt-4 inline-block"
      >
        {{ hasApplied(post) ? 'Already Applied' : 'Apply Now' }}
      </button>
      <p v-if="hasApplied(post)" class="text-xs text-green-600 mt-2">
        <a href="#my-applications" class="underline">View in My Applications</a>
      </p>
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

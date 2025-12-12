<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Test Centers by City and Post</h1>

    <!-- Induction Phase Dropdown -->
    <div class="mb-4">
      <label for="induction_phase" class="block text-sm font-medium text-gray-700">Select Induction Phase:</label>
      <select
        v-model="inductionPhaseId"
        @change="fetchCities"
        id="induction_phase"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      >
        <option value="">Select Induction Phase</option>
        <option v-for="phase in inductionPhases" :key="phase.id" :value="phase.id">{{ phase.title }}</option>
      </select>
    </div>

    <!-- City Dropdown -->
    <div class="mb-4">
      <label for="city" class="block text-sm font-medium text-gray-700">Select City:</label>
      <select
        v-model="cityId"
        @change="fetchPostsAndCenters"
        id="city"
        :disabled="!inductionPhaseId"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      >
        <option value="">Select City</option>
        <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
      </select>
    </div>

    <!-- Posts Table (All Posts for City) -->
    <div v-if="posts.length" class="mt-6">
      <h3 class="text-xl font-semibold mb-2">Posts and Applications in City</h3>
      <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="py-2 px-4 border-b text-left">Post Name</th>
            <th class="py-2 px-4 border-b text-left">Abbreviation</th>
            <th class="py-2 px-4 border-b text-left">Number of Applications</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b">{{ post.name }}</td>
            <td class="py-2 px-4 border-b">{{ post.post_abbreviation }}</td>
            <td class="py-2 px-4 border-b">{{ post.application_count }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Centers Table with Post Assignments and Application Counts -->
    <div v-if="centers.length" class="mt-6">
      <h3 class="text-xl font-semibold mb-2">Test Centers and Assigned Posts</h3>
      <div v-for="center in centers" :key="center.id" class="mb-6">
        <h4 class="text-lg font-medium">{{ center.name }} ({{ center.code }})</h4>
        <p class="text-sm text-gray-600">{{ center.address || 'N/A' }}</p>
        <table class="min-w-full bg-white border border-gray-200 mt-2">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-2 px-4 border-b text-left">Post Name</th>
              <th class="py-2 px-4 border-b text-left">Abbreviation</th>
              <th class="py-2 px-4 border-b text-left">Number of Applications</th>
              <th class="py-2 px-4 border-b text-left">Max Applicants</th>
              <th class="py-2 px-4 border-b text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!center.posts.length" class="hover:bg-gray-100">
              <td colspan="5" class="py-2 px-4 border-b text-center">No posts assigned</td>
            </tr>
            <tr v-for="post in center.posts" :key="post.id" class="hover:bg-gray-100">
              <td class="py-2 px-4 border-b">{{ post.name }}</td>
              <td class="py-2 px-4 border-b">{{ post.post_abbreviation }}</td>
              <td class="py-2 px-4 border-b">{{ post.application_count }}</td>
              <td class="py-2 px-4 border-b">
                <input
                  type="number"
                  v-model.number="maxApplicants[center.id + '-' + post.id]"
                  @blur="updateMaxApplicants(center.id, post.id)"
                  class="border-gray-300 rounded-md shadow-sm w-20"
                  min="0"
                  placeholder="Max"
                />
              </td>
              <td class="py-2 px-4 border-b">
                <button
                  @click="removePost(center.id, post.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="mt-2 flex items-center">
          <select
            v-model="pendingAssignments[center.id].post_id"
            @change="updatePendingAssignment(center.id, $event.target.value)"
            class="border-gray-300 rounded-md shadow-sm mr-2"
          >
            <option value="">Assign Post</option>
            <option v-for="post in allPosts" :key="post.id" :value="post.id">{{ post.post_abbreviation }}</option>
          </select>
          <input
            v-if="pendingAssignments[center.id]?.post_id"
            type="number"
            v-model.number="pendingAssignments[center.id].max_applicants"
            class="border-gray-300 rounded-md shadow-sm w-20"
            min="0"
            placeholder="Max Applicants"
          />
        </div>
      </div>
      <button
        v-if="hasPendingAssignments"
        @click="saveAssignments"
        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
      >
        Save Assignments
      </button>
    </div>

    <!-- Toast Notification -->
    <div v-if="toastMessage" class="fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-md shadow-lg">
      {{ toastMessage }}
    </div>

    <!-- Footer -->
    <footer class="mt-8 text-center text-gray-600">
      <p>© 2025 FGEI Induction. All rights reserved.</p>
      <p>Developed by IT CELL</p>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
  layout: AuthenticatedLayout,
});

// Props from Inertia
const props = defineProps({
  inductionPhases: Array,
  canAssign: Boolean,
});

// Reactive state
const inductionPhaseId = ref('');
const cityId = ref('');
const cities = ref([]);
const posts = ref([]);
const centers = ref([]);
const allPosts = ref([]);
const pendingAssignments = ref({});
const maxApplicants = ref({});
const toastMessage = ref('');

// Computed property to check if there are pending assignments
const hasPendingAssignments = computed(() => {
  return Object.values(pendingAssignments.value).some(
    assignment => assignment.post_id && assignment.max_applicants !== undefined
  );
});


// Initialize maxApplicants from pivot data
const initializeMaxApplicants = (centers) => {
  maxApplicants.value = {};
  centers.forEach(center => {
    center.posts.forEach(post => {
      maxApplicants.value[`${center.id}-${post.id}`] = post.pivot.max_applicants || 0;
    });
  });
};

// Update pending assignment
const updatePendingAssignment = (centerId, postId) => {
  if (!pendingAssignments.value[centerId]) {
    pendingAssignments.value[centerId] = {};
  }
  if (postId) {
    pendingAssignments.value[centerId] = {
      center_id: centerId,
      post_id: Number(postId), // Ensure post_id is a number
      max_applicants: pendingAssignments.value[centerId]?.max_applicants || null,
    };
  } else {
    delete pendingAssignments.value[centerId];
  }
};

// Fetch cities when induction phase changes
const fetchCities = async () => {
  cityId.value = '';
  posts.value = [];
  centers.value = [];
  allPosts.value = [];
  pendingAssignments.value = {};
  maxApplicants.value = {};
  if (inductionPhaseId.value) {
    try {
      const response = await axios.post('/test-centers/cities', {
        induction_phase_id: inductionPhaseId.value,
      });
      cities.value = response.data;
    } catch (error) {
      console.error('Error fetching cities:', error);
      showToast('Failed to fetch cities: ' + (error.response?.data?.error || error.message));
    }
  } else {
    cities.value = [];
  }
};

// Fetch posts, centers, and all posts when city changes
const fetchPostsAndCenters = async () => {
  posts.value = [];
  centers.value = [];
  allPosts.value = [];
  pendingAssignments.value = {};
  maxApplicants.value = {};
  if (inductionPhaseId.value && cityId.value) {
    try {
      const response = await axios.post('/test-centers/posts', {
        induction_phase_id: inductionPhaseId.value,
        city_id: cityId.value,
      });
      posts.value = response.data.posts || [];
      centers.value = response.data.centers || [];
      allPosts.value = response.data.allPosts || [];
      initializeMaxApplicants(centers.value);
    } catch (error) {
      console.error('Error fetching posts and centers:', error);
      showToast('Failed to fetch posts and centers: ' + (error.response?.data?.error || error.message));
    }
  }
};

// Save all pending assignments
const saveAssignments = async () => {
  try {
    const assignments = Object.values(pendingAssignments.value).filter(
      assignment => assignment.post_id && assignment.max_applicants !== undefined
    );
    if (assignments.length === 0) {
      showToast('No assignments to save');
      return;
    }
    const response = await axios.post('/test-centers/assign-posts', {
      assignments,
    });
    pendingAssignments.value = {};
    await fetchPostsAndCenters();
    showToast(response.data.message);
  } catch (error) {
    console.error('Error saving assignments:', error);
    showToast('Failed to save assignments: ' + (error.response?.data?.error || 'Server error'));
  }
};

// Update max applicants for a post-center assignment
const updateMaxApplicants = async (centerId, postId) => {
  try {
    const response = await axios.post('/test-centers/update-max-applicants', {
      center_id: centerId,
      post_id: postId,
      max_applicants: maxApplicants.value[`${centerId}-${postId}`] || null,
    });
    await fetchPostsAndCenters();
    showToast(response.data.message);
  } catch (error) {
    console.error('Error updating max applicants:', error);
    showToast('Failed to update max applicants: ' + (error.response?.data?.error || 'Server error'));
  }
};

// Remove a post from a center
const removePost = async (centerId, postId) => {
  try {
    const response = await axios.post('/test-centers/remove-post', {
      center_id: centerId,
      post_id: postId,
    });
    await fetchPostsAndCenters();
    showToast(response.data.message);
  } catch (error) {
    console.error('Error removing post:', error);
    showToast('Failed to remove post: ' + (error.response?.data?.error || 'Server error'));
  }
};

// Show toast notification
const showToast = (message) => {
  toastMessage.value = message;
  setTimeout(() => {
    toastMessage.value = '';
  }, 3000);
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 8px 12px;
  border: 1px solid #e5e7eb;
}
th {
  background-color: #f9fafb;
  font-weight: 600;
}
tr:hover {
  background-color: #f3f4f6;
}
select, button, input[type="number"] {
  padding: 6px;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
}
button {
  margin-left: 8px;
}
input[type="number"] {
  width: 80px;
}
footer {
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}
</style>
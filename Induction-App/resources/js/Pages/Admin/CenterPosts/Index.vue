<script setup>
import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    cities: Array,
});

const selectedCityId = ref(null);
const centers = ref([]);
const selectedCenter = ref(null);
const availablePosts = ref([]);
const loading = ref(false);
const showAssignmentModal = ref(false);
const selectedPosts = ref([]);
const assignmentData = ref({});
const originallyAssignedPosts = ref([]);

// Fetch centers when a city is selected
const selectCity = async (cityId) => {
    selectedCityId.value = cityId;
    selectedCenter.value = null;
    loading.value = true;

    try {
        const response = await fetch(route('center-posts.centers', cityId));
        const data = await response.json();
        centers.value = data.centers;
    } catch (error) {
        console.error('Error fetching centers:', error);
        alert('Failed to load centers');
    } finally {
        loading.value = false;
    }
};

// Select a center to view/manage its posts
const selectCenter = async (center) => {
    selectedCenter.value = center;
    showAssignmentModal.value = true;

    // Reset selection
    selectedPosts.value = [];
    assignmentData.value = {};

    // Fetch available posts for assignment
    loading.value = true;
    try {
        const response = await fetch(route('center-posts.available', center.id));
        const data = await response.json();
        availablePosts.value = data.posts;
        
        // Pre-select already assigned posts and populate their data
        originallyAssignedPosts.value = []; // Reset tracking
        data.posts.forEach(post => {
            if (post.is_assigned) {
                selectedPosts.value.push(post.id);
                originallyAssignedPosts.value.push(post.id); // Track original assignments
                assignmentData.value[post.id] = {
                    max_applicants: post.current_max_applicants,
                    assignment_id: post.assignment_id
                };
            }
        });
    } catch (error) {
        console.error('Error fetching posts:', error);
        alert('Failed to load available posts');
    } finally {
        loading.value = false;
    }
};

// Initialize assignment data for a post
const initAssignmentData = (postId) => {
    if (!assignmentData.value[postId]) {
        assignmentData.value[postId] = {};
    }
};

// Watch selected posts to initialize data
import { watch } from 'vue';
watch(selectedPosts, (newPosts) => {
    newPosts.forEach(postId => {
        if (!assignmentData.value[postId]) {
            assignmentData.value[postId] = {};
        }
    });
}, { deep: true });

// Handle post selection change (kept for compatibility but watcher handles init)
const handlePostSelection = (postId) => {
    // Logic handled by watcher
};

// Assign selected posts to center
const assignPosts = async () => {
    loading.value = true;
    
    try {
        // First, remove any posts that were deselected
        const postsToRemove = originallyAssignedPosts.value.filter(id => !selectedPosts.value.includes(id));
        
        for (const postId of postsToRemove) {
            const assignmentId = assignmentData.value[postId]?.assignment_id;
            if (assignmentId) {
                await fetch(route('center-posts.remove', assignmentId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
            }
        }
        
        // If no posts are selected after removal, just close and refresh
        if (selectedPosts.value.length === 0) {
            showAssignmentModal.value = false;
            selectedPosts.value = [];
            assignmentData.value = {};
            originallyAssignedPosts.value = [];
            selectCity(selectedCityId.value);
            loading.value = false;
            return;
        }

        // Then assign/update selected posts
        const assignments = selectedPosts.value.map(postId => ({
            post_id: postId,
            exam_schedule_id: assignmentData.value[postId]?.exam_schedule_id || null,
            max_applicants: assignmentData.value[postId]?.max_applicants || null,
        }));

        const response = await fetch(route('center-posts.assign'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                center_id: selectedCenter.value.id,
                assignments: assignments,
            }),
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            showAssignmentModal.value = false;
            selectedPosts.value = [];
            assignmentData.value = {};
            originallyAssignedPosts.value = [];
            // Refresh centers
            selectCity(selectedCityId.value);
        } else {
            alert(data.message || 'Failed to assign posts');
        }
    } catch (error) {
        console.error('Error assigning posts:', error);
        alert('Failed to assign posts');
    } finally {
        loading.value = false;
    }
};

// Remove post assignment from center
const removeAssignment = async (centerPostId) => {
    if (!confirm('Are you sure you want to remove this post assignment?')) {
        return;
    }

    loading.value = true;
    try {
        const response = await fetch(route('center-posts.remove', centerPostId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            // Refresh centers
            selectCity(selectedCityId.value);
        } else {
            alert(data.message || 'Failed to remove assignment');
        }
    } catch (error) {
        console.error('Error removing assignment:', error);
        alert('Failed to remove assignment');
    } finally {
        loading.value = false;
    }
};

// Get exam schedules for a post
const getSchedulesForPost = async (postId) => {
    try {
        const response = await fetch(route('center-posts.schedules', postId));
        const data = await response.json();
        return data.schedules;
    } catch (error) {
        console.error('Error fetching schedules:', error);
        return [];
    }
};

const searchQuery = ref('');

const filteredCities = computed(() => {
    if (!searchQuery.value) return props.cities;
    const query = searchQuery.value.toLowerCase();
    return props.cities.filter(city =>
        city.name.toLowerCase().includes(query) ||
        city.district?.name?.toLowerCase().includes(query)
    );
});

const selectedCityName = computed(() => {
    const city = props.cities.find(c => c.id === selectedCityId.value);
    return city ? city.name : '';
});

const isAssignButtonDisabled = computed(() => {
    return loading.value || selectedPosts.value.length === 0;
});

console.log('Selected Posts:', isAssignButtonDisabled);

const assignButtonText = computed(() => {
    if (loading.value) return 'Processing...';
    if (selectedPosts.value.length === 0) return 'Select Posts to Assign';
    
    const count = selectedPosts.value.length;
    const hasExisting = selectedPosts.value.some(id => assignmentData.value[id]?.assignment_id);
    
    if (hasExisting) {
        return `Update ${count} Post${count > 1 ? 's' : ''}`;
    }
    return `Assign ${count} Selected Post${count > 1 ? 's' : ''}`;
});
</script>

<template>
    <AdminLayout title="Center Wise Post Assignment">
        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold">Center Wise Post Assignment</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Manage exam post assignments across different test centers
                        </p>
                    </div>
                </div>

                <!-- City Selection -->
                <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                            <h3 class="text-lg font-semibold">1. Select Exam Station City</h3>

                            <!-- Search Input -->
                            <div class="relative w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Search cities..."
                                />
                            </div>
                        </div>

                        <!-- Scrollable City Grid -->
                        <div class="max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                <div
                                    v-for="city in filteredCities"
                                    :key="city.id"
                                    @click="selectCity(city.id)"
                                    :class="[
                                        'p-3 border rounded-lg cursor-pointer transition-all hover:shadow-md',
                                        selectedCityId === city.id
                                            ? 'border-purple-500 bg-purple-50 ring-1 ring-purple-500'
                                            : 'border-gray-200 hover:border-purple-300'
                                    ]"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ city.name }}</h4>
                                            <p class="text-xs text-gray-500 truncate max-w-[150px]">
                                                {{ city.district?.name }}, {{ city.district?.province?.name }}
                                            </p>
                                            <p class="text-xs font-medium text-blue-600 mt-1">
                                                Applicants: {{ city.approved_applicants_count }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 flex-shrink-0" title="Centers Count">
                                            <span class="text-xs font-bold text-purple-600">{{ city.centers_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p v-if="filteredCities.length === 0" class="text-center py-4 text-gray-500 text-sm">
                                {{ searchQuery ? 'No cities match your search.' : 'No exam station cities found.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Centers and Assignment Section -->
                <div v-if="selectedCityId" class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            2. Centers in {{ selectedCityName }}
                        </h3>

                        <div v-if="loading" class="py-8 text-center">
                            <div class="inline-block w-8 h-8 border-4 border-purple-600 rounded-full border-t-transparent animate-spin"></div>
                            <p class="mt-2 text-gray-600">Loading centers...</p>
                        </div>

                        <!-- Added max-height and overflow-y-auto for scrolling -->
                        <div v-else-if="centers.length > 0" class="space-y-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                            <div
                                v-for="center in centers"
                                :key="center.id"
                                class="p-4 border rounded-lg hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ center.name }}</h4>
                                        <p class="text-sm text-gray-600">{{ center.address }}</p>
                                        <div class="flex gap-4 mt-2 text-sm text-gray-500">
                                            <span>📍 {{ center.cities?.name }}</span>
                                            <span>👥 Capacity: {{ center.capacity }}</span>
                                            <span>📋 Assigned Posts: {{ center.posts_count }}</span>
                                        </div>

                                        <!-- Display assigned posts -->
                                        <div v-if="center.posts && center.posts.length > 0" class="mt-3">
                                            <p class="text-sm font-medium text-gray-700">Assigned Posts:</p>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <div
                                                    v-for="post in center.posts"
                                                    :key="post.id"
                                                    class="inline-flex items-center px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full"
                                                >
                                                    {{ post.post_abbreviation }}
                                                    <button
                                                        @click="removeAssignment(post.pivot.id)"
                                                        class="ml-2 text-red-600 hover:text-red-800"
                                                        title="Remove assignment"
                                                    >
                                                        ×
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        @click="selectCenter(center)"
                                        class="px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors"
                                    >
                                        Assign Posts
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p v-else class="text-gray-500">
                            No centers found in this city.
                        </p>
                    </div>
                </div>

                <!-- Assignment Modal -->
                <div
                    v-if="showAssignmentModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
                    @click.self="showAssignmentModal = false"
                >
                    <!-- Adjusted width (max-w-2xl instead of 3xl) and added height constraints -->
                    <div class="w-full max-w-2xl mx-4 bg-white rounded-xl shadow-2xl flex flex-col max-h-[85vh]">
                        <div class="p-6 border-b">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Assign Posts to {{ selectedCenter?.name }}</h3>
                                <button
                                    @click="showAssignmentModal = false"
                                    class="p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 overflow-y-auto flex-1">
                            <div class="mb-4">
                                <label class="block mb-3 text-sm font-medium text-gray-700">
                                    Select Posts to Assign
                                </label>
                                <!-- Increased max-height for posts list -->
                                <div class="space-y-3">
                                    <div
                                        v-for="post in availablePosts"
                                        :key="post.id"
                                        class="p-4 border rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-all"
                                        :class="{'border-purple-500 bg-purple-50': selectedPosts.includes(post.id)}"
                                    >
                                        <div class="flex items-start gap-4 w-full">
                                            <div class="pt-1">
                                                <input
                                                    type="checkbox"
                                                    :id="'post-' + post.id"
                                                    :value="post.id"
                                                    v-model="selectedPosts"
                                                    class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <label :for="'post-' + post.id" class="cursor-pointer block">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <div class="font-semibold text-gray-900">{{ post.name }}</div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ post.post_abbreviation }} • {{ post.category?.name }}
                                                                <span class="ml-2 text-blue-600 font-medium">
                                                                    ({{ post.approved_applicants_count }} Applicants)
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <span v-if="selectedPosts.includes(post.id)" class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded">Selected</span>
                                                    </div>
                                                </label>

                                                <!-- Max applicants input with animation -->
                                                <transition
                                                    enter-active-class="transition duration-200 ease-out"
                                                    enter-from-class="transform scale-95 opacity-0"
                                                    enter-to-class="transform scale-100 opacity-100"
                                                    leave-active-class="transition duration-150 ease-in"
                                                    leave-from-class="transform scale-100 opacity-100"
                                                    leave-to-class="transform scale-95 opacity-0"
                                                >
                                                    <div v-if="selectedPosts.includes(post.id)" class="mt-3 pl-1">
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Max Applicants (Optional)</label>
                                                        <input
                                                            type="number"
                                                            v-model="assignmentData[post.id].max_applicants"
                                                            placeholder="Enter limit"
                                                            class="w-full max-w-xs px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                                            min="1"
                                                            @click.stop
                                                        />
                                                    </div>
                                                </transition>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t bg-gray-50 rounded-b-xl flex justify-end gap-3">
                            <button
                                @click="showAssignmentModal = false"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                            >
                                Cancel
                            </button>
                            <button
                                @click="assignPosts"
                                :disabled="isAssignButtonDisabled"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:bg-gray-400 disabled:cursor-not-allowed shadow-sm transition-colors"
                            >
                                {{ assignButtonText }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>

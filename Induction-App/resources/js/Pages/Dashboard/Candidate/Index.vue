<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20">
    <!-- Hero Section with Stats -->
    <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white mb-8 rounded-2xl shadow-2xl">
      <div class="absolute inset-0 bg-black/10"></div>
      <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0djItaDJWMzZoLTJ6bTAtNGgydjJoLTJ2LTJ6bTAgNGgydjJoLTJ2LTJ6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-20"></div>

      <div class="relative px-4 sm:px-8 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto">
          <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
            <!-- Welcome Section -->
            <div class="flex-1">
              <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-4 py-2 rounded-full mb-4">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-medium">Active Dashboard</span>
              </div>
              <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-3 leading-tight">
                Welcome Back, <span class="text-yellow-300">{{ auth?.user?.name || 'Candidate' }}</span>
              </h1>
              <p class="text-lg text-teal-50 mb-6">Track your applications, discover new opportunities, and manage your career journey</p>

              <!-- Quick Stats -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20">
                  <div class="text-3xl font-bold">{{ myJobsApplication?.applied_posts?.length || 0 }}</div>
                  <div class="text-sm text-teal-100 mt-1">Applications</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20">
                  <div class="text-3xl font-bold">{{ posts?.length || 0 }}</div>
                  <div class="text-sm text-teal-100 mt-1">Open Posts</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20">
                  <div class="text-3xl font-bold">{{ approvedCount }}</div>
                  <div class="text-sm text-teal-100 mt-1">Approved</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-4 border border-white/20">
                  <div class="text-3xl font-bold">{{ pendingCount }}</div>
                  <div class="text-sm text-teal-100 mt-1">Pending</div>
                </div>
              </div>
            </div>

            <!-- Profile Card -->
            <div class="w-full lg:w-auto bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 shadow-2xl">
              <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-3xl font-bold text-white">
                  <img v-if="auth?.user?.candidate_profile?.photo_path" :src="`/storage/${auth.user.candidate_profile.photo_path}`" alt="Profile" class="w-full h-full object-cover" />
                  <span v-else>{{ auth?.user?.name?.charAt(0).toUpperCase() }}</span>
                </div>
                <div>
                  <div class="text-lg font-bold">{{ auth?.user?.name }}</div>
                  <div class="text-sm text-teal-100">{{ auth?.user?.email }}</div>
                  <div class="mt-2 inline-flex items-center gap-2 bg-green-500/30 px-3 py-1 rounded-full text-xs font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Verified Profile
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 space-y-6 sm:space-y-8">
      <!-- Announcements Carousel -->
      <Announcements :posts="posts" />

      <!-- Error Message -->
      <div v-if="error" class="bg-red-50 border-l-4 border-red-500 p-6 rounded-xl shadow-sm">
        <div class="flex items-start gap-3">
          <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
          </svg>
          <div>
            <h3 class="text-lg font-semibold text-red-800">Action Required</h3>
            <p class="text-red-700 mt-1">{{ error }}</p>
            <a :href="route('Candidate.Profile')" class="inline-flex items-center gap-2 mt-3 text-red-700 font-medium hover:text-red-900">
              Complete Your Profile
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid lg:grid-cols-3 gap-8">
        <!-- Left Column - Active Services -->
        <div class="lg:col-span-2 space-y-8">
          <ActiveServices
            :posts="posts"
            :error="error"
            :testCities="testCities"
            :myJobsApplication="myJobsApplication"
          />
        </div>

        <!-- Right Column - Quick Actions & Info -->
        <!-- Replace this entire Quick Actions card with this fixed version -->
<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
  <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
    <h3 class="text-lg font-bold text-white flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
      </svg>
      Quick Actions
    </h3>
  </div>
  <div class="p-6 space-y-3">

    <Link :href="route('Candidate.Profile')" class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 border border-blue-100">
      <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
      <div class="flex-1">
        <div class="font-semibold text-gray-900">My Profile</div>
        <div class="text-xs text-gray-600">Update your information</div>
      </div>
      <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </Link>

    <Link :href="route('Support.index')" class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 hover:from-emerald-100 hover:to-teal-100 transition-all duration-300 border border-emerald-100">
      <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
      </div>
      <div class="flex-1">
        <div class="font-semibold text-gray-900">Support</div>
        <div class="text-xs text-gray-600">Get help instantly</div>
      </div>
      <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </Link>

    <Link :href="route('applications.index')" class="group flex items-center gap-4 p-4 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 transition-all duration-300 border border-purple-100">
      <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div class="flex-1">
        <div class="font-semibold text-gray-900">Applications</div>
        <div class="text-xs text-gray-600">Track all submissions</div>
      </div>
      <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </Link>

  </div>
</div>
      </div>

      <!-- Applications Section -->
      <ApplicationsSection :myJobsApplication="myJobsApplication" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Candidatelayout from "@/Layouts/Candidatelayout.vue";
import Announcements from "@/Components/Candidate/Announcements.vue";
import { Link } from '@inertiajs/vue3'
import ActiveServices from "@/Components/Candidate/ActiveServices.vue";
import ApplicationsSection from "@/Components/Candidate/ApplicationsSection.vue";

defineOptions({
  layout: Candidatelayout,
});

const props = defineProps({
  posts: { type: Array, default: () => [] },
  myJobsApplication: { type: Object, default: () => ({}) },
  testCities: { type: Array, default: () => [] },
  error: String,
  auth: { type: Object, default: () => ({}) },
});

const approvedCount = computed(() => {
  return props.myJobsApplication?.applied_posts?.filter(app =>
    app.status?.toLowerCase().includes('approved') ||
    app.status?.toLowerCase().includes('roll number slip')
  ).length || 0;
});

const pendingCount = computed(() => {
  return props.myJobsApplication?.applied_posts?.filter(app =>
    app.status?.toLowerCase() === 'pending' || !app.status
  ).length || 0;
});
</script>

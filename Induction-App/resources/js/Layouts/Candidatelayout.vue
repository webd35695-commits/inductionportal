<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 font-sans antialiased">
    <Head :title="title" />

    <!-- Premium Navigation Header -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-gray-200 shadow-lg">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-20">
          <!-- Logo & Brand -->
          <Link :href="route('candidate.dashboard')" class="flex items-center gap-4 hover:opacity-80 transition-opacity">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl flex items-center justify-center shadow-xl">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-gray-900">FGEI Portal</h1>
              <p class="text-xs text-gray-600">Candidate Dashboard</p>
            </div>
          </Link>

          <!-- Mobile Menu Button -->
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
            aria-label="Toggle mobile menu"
          >
            <svg v-if="!mobileMenuOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg v-else class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <!-- Desktop Navigation Links -->
          <nav class="hidden lg:flex items-center gap-2">
            <Link
              :href="route('candidate.dashboard')"
              class="px-4 py-2 rounded-xl font-medium transition-all hover:bg-teal-50 hover:text-teal-700 flex items-center gap-2"
              :class="isCurrentRoute('candidate.dashboard') ? 'bg-teal-100 text-teal-700' : 'text-gray-700'"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              Dashboard
            </Link>

            <Link
              :href="route('Candidate.Profile')"
              class="px-4 py-2 rounded-xl font-medium transition-all hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2"
              :class="isCurrentRoute('Candidate.Profile') ? 'bg-blue-100 text-blue-700' : 'text-gray-700'"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Profile
            </Link>

            <Link
              :href="route('applications.index')"
              class="px-4 py-2 rounded-xl font-medium transition-all hover:bg-purple-50 hover:text-purple-700 flex items-center gap-2"
              :class="isCurrentRoute('applications.index') ? 'bg-purple-100 text-purple-700' : 'text-gray-700'"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Applications
            </Link>

            <Link
              :href="route('Support.index')"
              class="px-4 py-2 rounded-xl font-medium transition-all hover:bg-emerald-50 hover:text-emerald-700 flex items-center gap-2"
              :class="isCurrentRoute('Support.index') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-700'"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
              </svg>
              Support
            </Link>
          </nav>

          <!-- User Menu -->
          <div v-if="auth?.user" class="hidden lg:flex items-center gap-4">
            <!-- Notifications Bell -->
            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
              <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            </button>

            <!-- User Dropdown -->
            <Dropdown align="right" width="72">
              <template #trigger>
                <button class="flex items-center gap-3 p-2 pr-4 rounded-xl hover:bg-gray-100 transition-all group">
                  <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg group-hover:scale-105 transition-transform bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                    <img v-if="auth.user.candidate_profile?.photo_path" :src="`/storage/${auth.user.candidate_profile.photo_path}`" alt="Profile" class="w-full h-full object-cover" />
                    <span v-else>{{ auth.user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <div class="hidden md:block text-left">
                    <div class="text-sm font-semibold text-gray-900">{{ auth.user.name }}</div>
                    <div class="text-xs text-gray-600">{{ auth.user.email }}</div>
                  </div>
                  <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
              </template>

              <template #content>
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                  <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <div class="flex items-center gap-4">
                      <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl overflow-hidden flex items-center justify-center text-2xl font-bold text-white">
                        <img v-if="auth.user.candidate_profile?.photo_path" :src="`/storage/${auth.user.candidate_profile.photo_path}`" alt="Profile" class="w-full h-full object-cover" />
                        <span v-else>{{ auth.user.name.charAt(0).toUpperCase() }}</span>
                      </div>
                      <div>
                        <div class="font-bold text-lg text-white">{{ auth.user.name }}</div>
                        <div class="text-sm text-indigo-100">{{ auth.user.email }}</div>
                        <div class="mt-1 inline-flex items-center gap-1 bg-white/25 px-2 py-0.5 rounded-full text-xs font-medium text-white">
                          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                          </svg>
                          Verified Account
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="py-3">
                    <DropdownLink :href="route('Candidate.Profile')" class="flex items-center gap-3 px-6 py-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all">
                      <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900">My Profile</div>
                        <div class="text-xs text-gray-600">View and edit your profile</div>
                      </div>
                    </DropdownLink>

                    <DropdownLink :href="route('applications.index')" class="flex items-center gap-3 px-6 py-3 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all">
                      <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900">My Applications</div>
                        <div class="text-xs text-gray-600">Track your submissions</div>
                      </div>
                    </DropdownLink>

                    <DropdownLink :href="route('profile.edit')" class="flex items-center gap-3 px-6 py-3 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-all">
                      <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900">Settings</div>
                        <div class="text-xs text-gray-600">Account preferences</div>
                      </div>
                    </DropdownLink>

                    <div class="border-t border-gray-200 my-2"></div>

                    <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center gap-3 px-6 py-3 hover:bg-red-50 text-red-600 transition-all w-full text-left">
                      <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                      </div>
                      <div>
                        <div class="font-semibold">Logout</div>
                        <div class="text-xs">Sign out of your account</div>
                      </div>
                    </DropdownLink>
                  </div>
                </div>
              </template>
            </Dropdown>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="mobileMenuOpen" class="lg:hidden py-4 border-t border-gray-200 bg-white/90 backdrop-blur-md">
          <nav class="space-y-2">
            <Link :href="route('candidate.dashboard')" class="block px-4 py-3 rounded-xl font-medium transition-all hover:bg-teal-50 flex items-center gap-3"
              :class="isCurrentRoute('candidate.dashboard') ? 'bg-teal-100 text-teal-700' : 'text-gray-700'"
              @click="mobileMenuOpen = false">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              Dashboard
            </Link>

            <Link :href="route('Candidate.Profile')" class="block px-4 py-3 rounded-xl font-medium transition-all hover:bg-blue-50 flex items-center gap-3"
              :class="isCurrentRoute('Candidate.Profile') ? 'bg-blue-100 text-blue-700' : 'text-gray-700'"
              @click="mobileMenuOpen = false">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Profile
            </Link>

            <Link :href="route('applications.index')" class="block px-4 py-3 rounded-xl font-medium transition-all hover:bg-purple-50 flex items-center gap-3"
              :class="isCurrentRoute('applications.index') ? 'bg-purple-100 text-purple-700' : 'text-gray-700'"
              @click="mobileMenuOpen = false">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Applications
            </Link>

            <Link :href="route('Support.index')" class="block px-4 py-3 rounded-xl font-medium transition-all hover:bg-emerald-50 flex items-center gap-3"
              :class="isCurrentRoute('Support.index') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-700'"
              @click="mobileMenuOpen = false">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
              </svg>
              Support
            </Link>
          </nav>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import Footer from '@/Components/Candidate/Footer.vue'

defineProps({
  auth: {
    type: Object,
    default: () => ({ user: null }),
  },
  title: {
    type: String,
    default: 'FGEI Candidate Portal',
  },
})

const page = usePage()
const mobileMenuOpen = ref(false)

const isCurrentRoute = (routeName) => {
  try {
    const routeUrl = route(routeName)
    const currentUrl = page.url.split('?')[0] // ignore query params
    return currentUrl === routeUrl || currentUrl.startsWith(routeUrl + '/')
  } catch (e) {
    console.warn(`Route ${routeName} not found`)
    return false
  }
}
</script>

<style scoped>
.backdrop-blur-md {
  backdrop-filter: blur(12px);
}
</style>
